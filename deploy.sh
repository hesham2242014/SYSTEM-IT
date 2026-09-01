#!/usr/bin/env bash
#
# سكربت النشر على الخادم (cPanel).
# يُستدعى من GitHub Actions عبر SSH، أو من .cpanel.yml، أو يدويًا من Terminal.
#
# متغيرات يمكن ضبطها:
#   APP_DIR         مسار المشروع على الخادم   (افتراضي: مجلد السكربت)
#   DEPLOY_BRANCH   الفرع المنشور              (افتراضي: main)
#   PHP_BIN         مسار PHP                   (افتراضي: php)
#   COMPOSER_BIN    مسار Composer              (افتراضي: composer)
#   RUN_MIGRATIONS  تشغيل الترحيلات            (افتراضي: true)
#   SKIP_GIT        تخطّي سحب الكود من git     (افتراضي: false)

set -euo pipefail

APP_DIR="${APP_DIR:-$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-main}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
RUN_MIGRATIONS="${RUN_MIGRATIONS:-true}"
SKIP_GIT="${SKIP_GIT:-false}"

step() { printf '\n\033[1;34m==> %s\033[0m\n' "$1"; }

cd "$APP_DIR"
step "النشر في $APP_DIR (الفرع: $DEPLOY_BRANCH)"

if [ ! -f .env ]; then
    echo "خطأ: لا يوجد ملف .env في $APP_DIR" >&2
    echo "أنشئه مرة واحدة على الخادم: cp .env.example .env && $PHP_BIN artisan key:generate" >&2
    exit 1
fi

"$PHP_BIN" -v >/dev/null 2>&1 || { echo "خطأ: PHP غير موجود على المسار '$PHP_BIN'" >&2; exit 1; }

# لو فشل أي أمر بعد وضع الصيانة، نُخرج الموقع منها حتى لا يبقى معطّلًا.
restore() {
    "$PHP_BIN" artisan up >/dev/null 2>&1 || true
}

if [ "$SKIP_GIT" != "true" ]; then
    step "سحب آخر نسخة من الكود"
    git fetch --all --prune
    git reset --hard "origin/$DEPLOY_BRANCH"
fi

step "تفعيل وضع الصيانة"
"$PHP_BIN" artisan down --retry=15 >/dev/null 2>&1 || true
trap restore EXIT

step "تثبيت الاعتماديات"
"$COMPOSER_BIN" install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# نمسح فقط ذاكرة الملفات هنا. ذاكرة التطبيق (cache:clear) مخزّنة في قاعدة
# البيانات، وجدولها لا يكون موجودًا قبل أول ترحيل — لذلك نؤجّلها لما بعد migrate.
step "مسح الذاكرة المؤقتة القديمة"
"$PHP_BIN" artisan optimize:clear --except=cache

if [ "$RUN_MIGRATIONS" = "true" ]; then
    step "تشغيل ترحيلات قاعدة البيانات"
    "$PHP_BIN" artisan migrate --force
else
    step "تخطّي الترحيلات (RUN_MIGRATIONS=$RUN_MIGRATIONS)"
fi

step "مسح ذاكرة التطبيق"
"$PHP_BIN" artisan cache:clear || echo "تحذير: تعذّر مسح ذاكرة التطبيق — يتم التجاهل"

step "ربط مجلد التخزين"
"$PHP_BIN" artisan storage:link >/dev/null 2>&1 || true

step "بناء الذاكرة المؤقتة للإنتاج"
"$PHP_BIN" artisan optimize

step "ضبط صلاحيات المجلدات القابلة للكتابة"
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

step "إخراج الموقع من وضع الصيانة"
trap - EXIT
"$PHP_BIN" artisan up

step "تم النشر بنجاح ✅"
"$PHP_BIN" artisan --version
git log -1 --pretty='الإصدار المنشور: %h — %s'

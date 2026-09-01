# النشر التلقائي على cPanel

بعد الإعداد لمرة واحدة، **كل `git push` على الفرع المنشور يرفع التعديل على الموقع تلقائيًا**:

```
تعديل الكود  ←  push على GitHub  ←  GitHub Actions يشغّل الاختبارات
                                          ↓ (نجحت)
                                    SSH إلى cPanel
                                          ↓
                                    deploy.sh على الخادم
                                          ↓
                            git pull ← composer ← migrate ← cache
                                          ↓
                                    الموقع محدَّث ✅
```

إذا فشلت الاختبارات، **لا يتم النشر** ويبقى الموقع على آخر نسخة سليمة.

---

## ما تحتاج تجهيزه (مرة واحدة)

> ⚠️ **لا ترسل كلمات المرور أو المفاتيح في محادثة أو تضعها في الكود.**
> كل البيانات الحساسة تُضاف في **GitHub Secrets** وأنت وحدك من يراها.

### 1. تفعيل SSH على cPanel

من cPanel: **Terminal** — إن كان ظاهرًا فلديك SSH. وإلا اطلب تفعيله من الدعم الفني
(معظم خطط الاستضافة المدفوعة تدعمه).

سجّل هذه المعلومات:

| المعلومة | من أين تجدها |
| --- | --- |
| اسم الخادم / IP | cPanel ← الشريط الجانبي ← `Shared IP Address` أو اسم النطاق |
| اسم المستخدم | اسم مستخدم cPanel |
| المنفذ | غالبًا `22` — بعض الشركات تستخدم `2222` أو `21098` |

### 2. إنشاء مفتاح SSH للنشر

من **Terminal** داخل cPanel:

```bash
ssh-keygen -t ed25519 -C "github-deploy" -f ~/.ssh/github_deploy -N ""
cat ~/.ssh/github_deploy.pub >> ~/.ssh/authorized_keys
chmod 700 ~/.ssh && chmod 600 ~/.ssh/authorized_keys
cat ~/.ssh/github_deploy      # ← هذا هو المفتاح الخاص، انسخه كاملًا
```

انسخ المفتاح الخاص كاملًا **بما في ذلك** سطرَي البداية والنهاية:

```
-----BEGIN OPENSSH PRIVATE KEY-----
...
-----END OPENSSH PRIVATE KEY-----
```

### 3. جلب المشروع على الخادم لأول مرة

من Terminal في cPanel:

```bash
cd ~
git clone https://github.com/hesham2242014/SYSTEM-IT.git system-it
cd system-it

cp .env.example .env
php artisan key:generate
nano .env      # عدّل بيانات قاعدة البيانات و APP_URL (التفاصيل بالأسفل)
```

إذا كان المستودع خاصًا، استخدم رابط SSH أو
[Personal Access Token](https://github.com/settings/tokens) بدل كلمة المرور.

### 4. إضافة الأسرار في GitHub

**Settings ← Secrets and variables ← Actions ← New repository secret**

| الاسم | القيمة |
| --- | --- |
| `CPANEL_HOST` | اسم النطاق أو IP الخادم |
| `CPANEL_USER` | اسم مستخدم cPanel |
| `CPANEL_SSH_KEY` | المفتاح الخاص من الخطوة 2 |
| `CPANEL_APP_PATH` | `/home/اسم_المستخدم/system-it` |
| `CPANEL_PORT` | المنفذ — أضفه فقط إذا لم يكن `22` |
| `CPANEL_KNOWN_HOSTS` | اختياري (أمان أعلى) — ناتج `ssh-keyscan -H النطاق` |

وفي تبويب **Variables** بنفس الصفحة، أضف عند الحاجة:

| الاسم | متى تحتاجه |
| --- | --- |
| `CPANEL_PHP_BIN` | إذا لم يكن `php` هو الإصدار الصحيح — مثال: `/usr/local/bin/ea-php83` |
| `CPANEL_COMPOSER_BIN` | إذا كان Composer في مسار غير قياسي — مثال: `~/composer.phar` |

للتأكد من إصدار PHP الصحيح على الخادم:

```bash
ls /usr/local/bin/ea-php*
/usr/local/bin/ea-php83 -v
```

إذا لم يكن Composer مثبتًا:

```bash
cd ~ && curl -sS https://getcomposer.org/installer | php
# ثم اضبط المتغير CPANEL_COMPOSER_BIN على: /home/اسم_المستخدم/composer.phar
```

### 5. توجيه النطاق إلى مجلد `public`

Laravel يجب أن يخدم من مجلد `public` فقط — وإلا أصبح ملف `.env` وكل الكود
متاحًا للتحميل من المتصفح.

**الطريقة الأفضل** — من cPanel ← **Domains**، اضبط
`Document Root` على `/home/اسم_المستخدم/system-it/public`.

**إن لم تستطع تغييره** — استبدل `public_html` برابط رمزي:

```bash
cd ~
mv public_html public_html_backup
ln -s ~/system-it/public ~/public_html
```

### 6. إعداد `.env` على الخادم

```env
APP_NAME="نظام بيانات الموظفين"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cpaneluser_system_it
DB_USERNAME=cpaneluser_dbuser
DB_PASSWORD=كلمة_المرور
```

> `APP_DEBUG=false` **ضروري** في الإنتاج — عند `true` تُعرض تفاصيل الأخطاء
> وبيانات الاتصال لأي زائر.

أنشئ قاعدة البيانات من cPanel ← **MySQL® Databases**، وامنح المستخدم
صلاحية `ALL PRIVILEGES`. لاحظ أن cPanel يضيف بادئة اسم حسابك تلقائيًا
لأسماء قواعد البيانات والمستخدمين.

### 7. الاختبار

```bash
git commit --allow-empty -m "اختبار النشر" && git push
```

تابع التنفيذ من تبويب **Actions** في GitHub.

---

## بعد ذلك

كل ما تحتاجه هو:

```bash
git push
```

والباقي تلقائي.

---

## بدون SSH: النشر عبر FTP

إذا لم توفّر استضافتك SSH، استخدم `.github/workflows/deploy-ftp.yml`:

1. أضف الأسرار: `FTP_HOST`، `FTP_USER`، `FTP_PASSWORD`، `FTP_APP_PATH`.
2. أزل التعليق عن قسم `push` في بداية الملف.
3. عطّل `deploy.yml` حتى لا ينشر الاثنان معًا.

**قيود مهمة لهذه الطريقة:**

- لا يمكن تشغيل `php artisan migrate` تلقائيًا. عند أي تعديل على قاعدة
  البيانات، شغّل الترحيل يدويًا من cPanel ← Terminal أو عبر Cron Job.
- الرفع أبطأ لأن مجلد `vendor` يُرفع بالكامل.
- FTP العادي يرسل كلمة المرور بدون تشفير — الملف يستخدم `ftps`، وتأكد أن
  استضافتك تدعمه.

لذلك **SSH هو الخيار الموصى به** كلما كان متاحًا.

---

## النشر اليدوي من لوحة cPanel

ملف `.cpanel.yml` يتيح النشر من cPanel ← **Git Version Control** ←
**Manage** ← **Deploy HEAD Commit**. عدّل `CPANEL_USER` في الملف إلى اسم
مستخدمك أولًا. هذا مفيد كخطة احتياطية، لكنه يتطلب ضغطة زر في كل مرة.

---

## حل المشكلات

| المشكلة | السبب والحل |
| --- | --- |
| `Permission denied (publickey)` | المفتاح العام لم يُضف إلى `~/.ssh/authorized_keys`، أو صلاحيات `~/.ssh` ليست `700` |
| `deploy.sh: No such file` | `CPANEL_APP_PATH` خطأ، أو المشروع لم يُستنسخ على الخادم (الخطوة 3) |
| `لا يوجد ملف .env` | أنشئه على الخادم مرة واحدة — `.env` غير مرفوع في git عمدًا |
| صفحة بيضاء | راجع `storage/logs/laravel.log`، وتأكد أن صلاحيات `storage` و `bootstrap/cache` هي `775` |
| `SQLSTATE[HY000] [1045]` | بيانات قاعدة البيانات في `.env` خاطئة، أو المستخدم بلا صلاحيات |
| الموقع عالق في وضع الصيانة | `php artisan up` من Terminal |
| التعديلات لا تظهر | `php artisan optimize:clear` — قد تكون ذاكرة قديمة |
| الكود يظهر بدل الصفحة | النطاق لا يشير إلى مجلد `public` (الخطوة 5) |

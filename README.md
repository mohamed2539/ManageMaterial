# نظام إدارة المواد (Material Management System)

## نظرة عامة
نظام متكامل لإدارة المواد والمخزون، يتيح للمؤسسات إدارة مواردها بكفاءة وفعالية.

## المميزات الرئيسية
- إدارة المخزون بشكل كامل
- تتبع حركة المواد
- إدارة الموردين
- تقارير وإحصائيات متقدمة
- واجهة مستخدم سهلة الاستخدام

## المتطلبات الأساسية
- PHP 8.0 أو أحدث
- MySQL 5.7 أو أحدث
- Composer
- Node.js و npm
- خادم ويب (Apache/Nginx)

## التثبيت

1. استنساخ المشروع:
```bash
git clone https://github.com/mohamed2539/ManageMaterial.git
cd ManageMaterial
```

2. تثبيت اعتماديات PHP:
```bash
composer install
```

3. تثبيت اعتماديات JavaScript:
```bash
npm install
```

4. إعداد ملف البيئة:
```bash
cp .env.example .env
```
ثم قم بتعديل المتغيرات في ملف .env حسب إعدادات بيئتك.

5. إنشاء قاعدة البيانات:
```bash
php artisan migrate
```

6. تشغيل المشروع:
```bash
php artisan serve
```

## هيكل المشروع
```
app/
  ├── controllers/    # معالجات الطلبات
  ├── models/         # نماذج قاعدة البيانات
  ├── views/         # ملفات العرض
  └── helpers/       # دوال مساعدة
config/              # ملفات الإعداد
public/              # الملفات العامة
  ├── assets/        # الأصول (CSS, JS, Images)
  └── index.php      # نقطة الدخول
```

## الأمان
- جميع المدخلات يتم تنقيتها
- حماية ضد هجمات XSS و CSRF
- تشفير البيانات الحساسة
- نظام صلاحيات متقدم

## المساهمة
نرحب بمساهماتكم! يرجى اتباع الخطوات التالية:
1. عمل Fork للمشروع
2. إنشاء فرع جديد (`git checkout -b feature/AmazingFeature`)
3. عمل Commit للتغييرات (`git commit -m 'Add some AmazingFeature'`)
4. رفع التغييرات (`git push origin feature/AmazingFeature`)
5. فتح طلب Pull Request

## الترخيص
هذا المشروع مرخص تحت رخصة MIT - انظر ملف [LICENSE](LICENSE) للتفاصيل.

## الدعم
للمساعدة والاستفسارات، يرجى فتح issue جديد في صفحة المشروع على GitHub.

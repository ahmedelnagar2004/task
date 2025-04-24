# نظام متابعة تقدم الموظفين في الدورات التدريبية

نظام يسمح للموظفين بالتسجيل في الدورات التدريبية، ومتابعة تقدمهم، وإجراء الاختبارات، والحصول على شهادات بعد إتمام الدورة.

## المتطلبات

- PHP >= 8.0
- Laravel 12.x
- MySQL
- Composer



2. تثبيت المتطلبات:
```bash
composer install
```

3. إنشاء ملف البيئة:
```bash
cp .env.example .env
```

4. تعديل ملف .env وإضافة معلومات قاعدة البيانات

5. إنشاء مفتاح التطبيق:
```bash
php artisan key:generate
```

6. إنشاء مفتاح JWT:
```bash
php artisan jwt:secret
```

7. تنفيذ الهجرات:
```bash
php artisan migrate
```

## نقاط النهاية API

### المصادقة
- تسجيل مستخدم جديد: `POST /api/auth/register`
- تسجيل الدخول: `POST /api/auth/login`
- تسجيل الخروج: `POST /api/auth/logout`
- تحديث التوكن: `POST /api/auth/refresh`
- بيانات المستخدم: `GET /api/auth/me`

### الموظفين
- قائمة الموظفين: `GET /api/employees`
- إضافة موظف: `POST /api/employees`
- عرض موظف: `GET /api/employees/{id}`
- تحديث موظف: `PUT /api/employees/{id}`
- حذف موظف: `DELETE /api/employees/{id}`
- دورات الموظف: `GET /api/employees/{id}/courses`

### الدورات التدريبية
- قائمة الدورات: `GET /api/courses`
- إضافة دورة: `POST /api/courses`
- عرض دورة: `GET /api/courses/{id}`
- تحديث دورة: `PUT /api/courses/{id}`
- حذف دورة: `DELETE /api/courses/{id}`
- موظفي الدورة: `GET /api/courses/{id}/employees`
- تسجيل موظف في دورة: `POST /api/courses/{id}/enroll`

### الدروس
- قائمة دروس الدورة: `GET /api/courses/{course}/lessons`
- إضافة درس: `POST /api/courses/{course}/lessons`
- عرض درس: `GET /api/courses/{course}/lessons/{id}`
- تحديث درس: `PUT /api/courses/{course}/lessons/{id}`
- حذف درس: `DELETE /api/courses/{course}/lessons/{id}`
- إعادة ترتيب الدروس: `POST /api/courses/{course}/lessons/reorder`

### الاختبارات
- قائمة اختبارات الدورة: `GET /api/courses/{course}/quizzes`
- إضافة اختبار: `POST /api/courses/{course}/quizzes`
- عرض اختبار: `GET /api/courses/{course}/quizzes/{id}`
- تحديث اختبار: `PUT /api/courses/{course}/quizzes/{id}`
- حذف اختبار: `DELETE /api/courses/{course}/quizzes/{id}`
- تقديم إجابات: `POST /api/courses/{course}/quizzes/{id}/submit`

### الأسئلة
- قائمة أسئلة الاختبار: `GET /api/courses/{course}/quizzes/{quiz}/questions`
- إضافة سؤال: `POST /api/courses/{course}/quizzes/{quiz}/questions`
- عرض سؤال: `GET /api/courses/{course}/quizzes/{quiz}/questions/{id}`
- تحديث سؤال: `PUT /api/courses/{course}/quizzes/{quiz}/questions/{id}`
- حذف سؤال: `DELETE /api/courses/{course}/quizzes/{quiz}/questions/{id}`

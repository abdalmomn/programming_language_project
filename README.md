#تطبيق مستودع أدوية
##توصيف التطبيق:

### هو **تطبيق** للتواصل بين الصيدلاني ومستودع الادوية, حيث يقوم الصيدلاني بالتواصل مع صاحب المستودع لتلبية احتياجاته عن طريق طلب طلبية من المستودع بشكل دوري وبناءً على احتياجات الصيدلاني والأدوية التي يحتاجها.

---

## ميزات التطبيق :

1. السماح للمسنخدم بتسجيل الدخول والخروج بسهولة من التطبيق
1. تسجيل الدخول عن طريق الاسم ورقم الجوال فقط.
1. امكانية تصفح الأدوية المتوفرة حسب تصنيفها.
1. امكانية البحث عن دواء معين حسب الاسم أو التصنيف
1. السماح للمصتخدم من عرض تفاصيل الأدوية المتاحة من السعر والاسم العلمي والتجاري وغيره.
1. امكانية طلب طلبيات متعددة ومن أكثر من مستودع في وقت واحد.
1. عرض طلبيات المستخدم في مكان واحد وتزويده بالقدرة على متابعة حالة كل طلب على حدى.
1. السماح لاصحاب المستودعات بإضافة أدوية جديدة عند الحاجة.
1. تمكين صاحب المستودع من استعراض الطلبات وتغيير حالتها الحالية.

---

### التقنيات التي تم استخدامها:

#### تم انشاء _3controller_ لكامل المشروع مع المودل الخاصة بها.

1. AuthController:

#### خاص بعملية تسجيل الدخول والخروج لكل من المستخدم وصاحب المستودع مع عملية انشاء _token_ لكل مستخدم.

#### التقنية المستخدمة : laravel jwt authentication.

1. CategoryController:

#### خاص بتصنيفات الادوية والعمليات عليها من حذف وبحث وعرض.

1. MedicineController:

#### خاص بالادوية واللعمليات عليها وإيضا فيه التوابع الخاصة بالطبات.

**تم استخدام eloquent model لكل عمليات الحذف والاضافة والتعديل.**

---

**تمت إضافة ملف postman collection للمشروع**

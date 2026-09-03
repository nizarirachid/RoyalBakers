<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\PricingConfig;
use App\Models\MaterialPrice;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Roles
        $roles = ['super-admin', 'admin', 'editor', 'teacher', 'customer'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => env('SUPER_ADMIN_EMAIL', 'nizarirachid@gmail.com')],
            [
                'name'                  => env('SUPER_ADMIN_NAME', 'NIZARI Rachid'),
                'email'                 => env('SUPER_ADMIN_EMAIL', 'nizarirachid@gmail.com'),
                'password'              => Hash::make(env('SUPER_ADMIN_PASSWORD', 'Fatima@1977')),
                'status'                => 'active',
                'force_password_change' => true,
                'preferred_language'    => 'ar',
                'email_verified_at'     => now(),
            ]
        );
        $superAdmin->syncRoles(['super-admin']);

        // Demo customer account
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name'                  => 'زبون تجريبي',
                'password'              => Hash::make('Customer@123'),
                'status'                => 'active',
                'preferred_language'    => 'ar',
                'email_verified_at'     => now(),
                'force_password_change' => false,
            ]
        );
        $customer->syncRoles(['customer']);

        // Settings
        $defaultSettings = [
            'site_name_ar'          => 'النزاري للخط العربي',
            'site_name_fr'          => 'NIZARI Calligraphie',
            'site_name_en'          => 'NIZARI Arabic Calligraphy',
            'contact_email'         => 'nizarirachid@gmail.com',
            'contact_phone'         => '+212 663 690 212',
            'contact_address_ar'    => 'النزاري رشيد، صندوق البريد 29 اثنين أوريكة، إقليم الحوز، مراكش، المغرب',
            'site_description_ar'   => 'فن الخط العربي الأصيل — لوحات فنية مخصصة تجمع بين الجماليات الأندلسية والزليج المغربي',
            'facebook_url'          => 'https://facebook.com/nizaricalligraphy',
            'instagram_url'         => 'https://instagram.com/nizaricalligraphy',
            'youtube_url'           => 'https://youtube.com/@nizaricalligraphy',
            'whatsapp_number'       => '+212663690212',
        ];
        foreach ($defaultSettings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // Pricing Config
        PricingConfig::firstOrCreate(
            ['id' => 1],
            [
                'price_per_cm'                  => 2.50,
                'digital_copy_price'            => 150.00,
                'shipping_local_price'          => 50.00,
                'shipping_international_price'  => 300.00,
                'currency'                      => 'MAD',
            ]
        );

        // Material Prices
        $materials = [
            ['name_ar' => 'ورق أرشيفي',       'name_fr' => 'Papier archivage',  'name_en' => 'Archival Paper',     'price_per_unit' => 2.00,  'unit' => 'cm²'],
            ['name_ar' => 'ورق مقوى ممتاز',    'name_fr' => 'Carton premium',    'name_en' => 'Premium Cardboard',  'price_per_unit' => 3.50,  'unit' => 'cm²'],
            ['name_ar' => 'جلد طبيعي',         'name_fr' => 'Cuir naturel',      'name_en' => 'Natural Leather',    'price_per_unit' => 8.00,  'unit' => 'cm²'],
            ['name_ar' => 'قماش كتاني',        'name_fr' => 'Toile de lin',      'name_en' => 'Linen Canvas',       'price_per_unit' => 5.00,  'unit' => 'cm²'],
            ['name_ar' => 'ورق بردي',          'name_fr' => 'Papier papyrus',    'name_en' => 'Papyrus Paper',      'price_per_unit' => 6.00,  'unit' => 'cm²'],
            ['name_ar' => 'خشب منقوش',         'name_fr' => 'Bois gravé',        'name_en' => 'Engraved Wood',      'price_per_unit' => 12.00, 'unit' => 'cm²'],
        ];
        foreach ($materials as $mat) {
            MaterialPrice::firstOrCreate(['name_ar' => $mat['name_ar']], $mat);
        }

        // Categories
        $categories = [
            ['name_ar' => 'خط الثلث',     'name_fr' => 'Calligraphie Thuluth',    'name_en' => 'Thuluth Calligraphy',    'slug' => 'thuluth',    'sort_order' => 1],
            ['name_ar' => 'خط النسخ',     'name_fr' => 'Calligraphie Naskh',      'name_en' => 'Naskh Calligraphy',      'slug' => 'naskh',      'sort_order' => 2],
            ['name_ar' => 'خط الكوفي',    'name_fr' => 'Calligraphie Kufique',    'name_en' => 'Kufic Calligraphy',      'slug' => 'kufic',      'sort_order' => 3],
            ['name_ar' => 'خط الديواني',  'name_fr' => 'Calligraphie Diwani',     'name_en' => 'Diwani Calligraphy',     'slug' => 'diwani',     'sort_order' => 4],
            ['name_ar' => 'خط الفارسي',   'name_fr' => 'Calligraphie Persane',    'name_en' => 'Persian Calligraphy',    'slug' => 'farsi',      'sort_order' => 5],
            ['name_ar' => 'أعمال قرآنية', 'name_fr' => 'Art Coranique',           'name_en' => 'Quranic Art',            'slug' => 'quranic',    'sort_order' => 6],
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // FAQs
        $faqs = [
            [
                'question_ar' => 'كيف أطلب لوحة خطية مخصصة؟',
                'answer_ar'   => 'يمكنك تقديم طلبك عبر صفحة "اطلب لوحة" حيث تحدد الأبعاد والنص والأسلوب الخطي وطريقة التوصيل. سيتواصل معك الأستاذ لتأكيد التفاصيل.',
                'sort_order'  => 1,
                'active'      => true,
            ],
            [
                'question_ar' => 'كم تستغرق مدة إنجاز اللوحة؟',
                'answer_ar'   => 'تتراوح مدة الإنجاز بين 7 و21 يوماً حسب الأبعاد وتعقيد التصميم. يمكن الاتفاق على مواعيد استعجالية بتكلفة إضافية.',
                'sort_order'  => 2,
                'active'      => true,
            ],
            [
                'question_ar' => 'ما هي طرق الدفع المتاحة؟',
                'answer_ar'   => 'نقبل الدفع عبر PayPal وبطاقات Visa/Mastercard والتحويل البنكي وCash on Delivery داخل المغرب.',
                'sort_order'  => 3,
                'active'      => true,
            ],
            [
                'question_ar' => 'هل يمكن الطلب من خارج المغرب؟',
                'answer_ar'   => 'نعم، نشحن إلى جميع الدول العربية والأوروبية. تكلفة الشحن الدولي تُحدد حسب الوجهة والأبعاد.',
                'sort_order'  => 4,
                'active'      => true,
            ],
            [
                'question_ar' => 'هل تتوفر نسخة رقمية من اللوحة؟',
                'answer_ar'   => 'نعم، يمكنك طلب نسخة رقمية عالية الدقة (300 DPI) مع أو بدون اللوحة الأصلية بتكلفة إضافية 150 درهم.',
                'sort_order'  => 5,
                'active'      => true,
            ],
        ];
        foreach ($faqs as $faq) {
            Faq::firstOrCreate(['sort_order' => $faq['sort_order']], $faq);
        }

        // Testimonials
        $testimonials = [
            [
                'client_name'    => 'أحمد بن موسى',
                'client_country' => 'المغرب',
                'testimonial_ar' => 'لوحة بديعة كتبها الأستاذ النزاري بخط الثلث. جمعت بين الأصالة والجمال، وصلتني في الوقت المحدد. أنصح بها بشدة.',
                'rating'         => 5,
                'active'         => true,
            ],
            [
                'client_name'    => 'Karim Mansouri',
                'client_country' => 'France',
                'testimonial_ar' => 'طلبت آية قرآنية للمنزل وكانت النتيجة مذهلة. الخط الديواني بدقة رائعة وألوان ساحرة. شكراً جزيلاً أستاذ.',
                'rating'         => 5,
                'active'         => true,
            ],
            [
                'client_name'    => 'Sara Al-Rashid',
                'client_country' => 'Saudi Arabia',
                'testimonial_ar' => 'تسجّلت في دورة الخط المبتدئة وكانت التجربة ممتازة. الأستاذ صبور ومحترف، تعلمت أسس الخط في وقت قصير.',
                'rating'         => 5,
                'active'         => true,
            ],
        ];
        foreach ($testimonials as $t) {
            Testimonial::firstOrCreate(['client_name' => $t['client_name']], $t);
        }

        // Sample Blog Post
        BlogPost::firstOrCreate(
            ['slug' => 'introduction-arabic-calligraphy'],
            [
                'author_id'    => $superAdmin->id,
                'title_ar'     => 'مدخل إلى عالم الخط العربي: التاريخ والأساليب',
                'slug'         => 'introduction-arabic-calligraphy',
                'content_ar'   => "الخط العربي فن راسخ الجذور في الحضارة الإسلامية، يمتد تاريخه لأكثر من أربعة عشر قرناً. نشأ مع ظهور الإسلام وتطور ليعكس جماليات الروح العربية في كل حرف ولفظة.\n\nأبرز أساليب الخط العربي:\n\n1. خط الثلث: أكثر الأنماط الخطية تعقيداً وأجمالها، يُستخدم في العناوين واللوحات الفنية والمباني الأثرية.\n\n2. خط النسخ: أكثر الخطوط انتشاراً وأسهلها قراءةً، ويُستخدم في طباعة المصاحف والكتب.\n\n3. خط الكوفي: أقدم أساليب الخط العربي، تتميز حروفه بالزوايا والأشكال الهندسية.\n\n4. خط الديواني: ابتكره العثمانيون، يتسم بالتعقيد والنعومة والزخارف المتشابكة.\n\nكل أسلوب خطي له قواعده وأدواته وجمالياته الخاصة، وتعلّمه رحلة روحية تجمع بين الصبر والإبداع.",
                'excerpt_ar'   => 'استكشف تاريخ الخط العربي وتعرّف على أبرز أساليبه من الثلث إلى الكوفي والديواني.',
                'status'       => 'published',
                'published_at' => now()->subDays(3),
                'views'        => 120,
            ]
        );
    }
}

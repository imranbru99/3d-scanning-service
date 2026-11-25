<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(512) 555-0100',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@pointforgereality.com'],
            [
                'name' => 'Elena Marsh',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(512) 555-0101',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'ops@pointforgereality.com'],
            [
                'name' => 'Jon Vega',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(512) 555-0102',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'Pointforge Reality Capture',
            'company_phone' => '(512) 555-0148',
            'company_email' => 'hello@pointforgereality.com',
            'whatsapp' => '15125550148',
            'service_area' => 'Austin, Round Rock, San Marcos, Georgetown & Central Texas',
            'tax_rate' => '8.25',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Terrestrial Laser Scan Day Rate', 'sku' => 'SCAN-DAY-TLS', 'category' => 'field', 'unit_cost' => 620, 'unit_price' => 1450, 'stock_qty' => 60, 'unit' => 'day'],
            ['name' => 'Mobile Mapping Day Rate', 'sku' => 'SCAN-DAY-MOB', 'category' => 'field', 'unit_cost' => 780, 'unit_price' => 1750, 'stock_qty' => 24, 'unit' => 'day'],
            ['name' => 'Scan Target & Control Kit (rental)', 'sku' => 'KIT-TGT-01', 'category' => 'equipment', 'unit_cost' => 45, 'unit_price' => 120, 'stock_qty' => 40, 'unit' => 'day'],
            ['name' => 'Point Cloud Registration Package', 'sku' => 'PROC-REG-M', 'category' => 'processing', 'unit_cost' => 180, 'unit_price' => 420, 'stock_qty' => 200, 'unit' => 'project'],
            ['name' => 'Scan-to-CAD Deliverable (Revit LOD 200)', 'sku' => 'DEL-CAD-R200', 'category' => 'deliverable', 'unit_cost' => 350, 'unit_price' => 890, 'stock_qty' => 80, 'unit' => 'level'],
            ['name' => 'Registered Point Cloud Export (.e57/.rcp)', 'sku' => 'DEL-PC-EXP', 'category' => 'deliverable', 'unit_cost' => 90, 'unit_price' => 245, 'stock_qty' => 500, 'unit' => 'export'],
            ['name' => 'TruView / Web Viewer Hosting (month)', 'sku' => 'HOST-TV-M', 'category' => 'hosting', 'unit_cost' => 25, 'unit_price' => 75, 'stock_qty' => 999, 'unit' => 'month'],
            ['name' => 'Night / Weekend Mobilization Surcharge', 'sku' => 'SUR-OFF-HR', 'category' => 'surcharge', 'unit_cost' => 85, 'unit_price' => 195, 'stock_qty' => 100, 'unit' => 'hour'],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['sku' => $product['sku']],
                $product + ['is_active' => true]
            );
        }

        $customers = collect([
            ['name' => 'Riley Harmon', 'email' => 'riley@example.com', 'phone' => '(512) 555-2201', 'company' => null, 'city' => 'Austin', 'state' => 'TX', 'zip' => '78701', 'address' => '301 Congress Ave'],
            ['name' => 'Maya Collins', 'email' => 'maya@hillcountryvdc.com', 'phone' => '(512) 555-2210', 'company' => 'Hill Country VDC', 'city' => 'Round Rock', 'state' => 'TX', 'zip' => '78664', 'address' => '1200 Louis Henna Blvd'],
            ['name' => 'Dev Patel', 'email' => 'dev@texashealthbuild.com', 'phone' => '(512) 555-2299', 'company' => 'Texas Health Build', 'city' => 'San Marcos', 'state' => 'TX', 'zip' => '78666', 'address' => '450 River Rd'],
            ['name' => 'Carmen Alves', 'email' => 'carmen.alves@example.com', 'phone' => '(512) 555-2188', 'company' => null, 'city' => 'Georgetown', 'state' => 'TX', 'zip' => '78626', 'address' => '812 S Main St'],
            ['name' => 'Noah Whitfield', 'email' => 'noah@cedarparkarch.com', 'phone' => '(512) 555-2177', 'company' => 'Cedar Park Architecture Group', 'city' => 'Cedar Park', 'state' => 'TX', 'zip' => '78613', 'address' => '900 Brushy Creek Rd'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'Downtown tower shell as-built', 'service_type' => 'as_built', 'status' => 'scheduled', 'sq_ft' => 185000, 'quoted_amount' => 28500],
            ['customer' => 1, 'title' => 'MEP mezzanine reality capture', 'service_type' => 'interior_mep', 'status' => 'in_progress', 'sq_ft' => 42000, 'quoted_amount' => 12400],
            ['customer' => 2, 'title' => 'Hospital wing scan-to-BIM LOD 200', 'service_type' => 'scan_cad', 'status' => 'quoted', 'sq_ft' => 95000, 'quoted_amount' => 38200],
            ['customer' => 3, 'title' => 'Campus civil topo + utilities', 'service_type' => 'topo', 'status' => 'completed', 'sq_ft' => 12, 'quoted_amount' => 6800, 'final_amount' => 7100],
            ['customer' => 4, 'title' => 'Clash verification — Level 3 chases', 'service_type' => 'clash', 'status' => 'invoiced', 'sq_ft' => 8500, 'quoted_amount' => 4200, 'final_amount' => 4200],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo scan job for Pointforge ERP. sq_ft stores approximate GSF or acreage per job type.',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * 0.0825, 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * 1.0825, 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * 1.0825, 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'ach',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Tower shell — control & first positions'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'measure',
                'starts_at' => now()->addDays(2)->setTime(7, 30),
                'ends_at' => now()->addDays(2)->setTime(16, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'MEP mezzanine scan day 1'],
            [
                'customer_id' => $customers[1]->id,
                'project_id' => $projectModels[1]->id,
                'type' => 'install',
                'starts_at' => now()->addDays(5)->setTime(18, 0),
                'ends_at' => now()->addDays(5)->setTime(23, 0),
                'status' => 'confirmed',
                'location' => $customers[1]->address,
            ]
        );

        $posts = [
            [
                'title' => 'Why Control Targets Matter Before the First Scan Position',
                'excerpt' => 'Registration drift shows up in CAD weeks later — here is how Pointforge locks control early.',
                'cover' => 'https://images.unsplash.com/photo-1581092162384-8987a1d9d588?auto=format&fit=crop&w=1400&q=80',
                'body' => "Unregistered clouds look fine in a viewer until someone snaps a dimension in Revit. Pointforge places control and checker targets before interior or façade work so every scan position shares a defensible coordinate frame.\n\nShare your survey control or request ours before mobilization. It saves a full day of rework on large Austin commercial shells.",
            ],
            [
                'title' => 'Choosing Point Cloud vs Scan-to-BIM Deliverables',
                'excerpt' => 'Not every coordination meeting needs modeled duct — scope the tier that matches the decision.',
                'cover' => 'https://images.unsplash.com/photo-1582719478250-c89cae7dc08b?auto=format&fit=crop&w=1400&q=80',
                'body' => "Point clouds excel at verification and visuals. BIM modeling pays off when trades need clash-ready geometry. Pointforge quotes cloud registration, TruView hosting, and LOD-specific modeling as separate SKUs so invoices match what VDC actually uses.",
            ],
            [
                'title' => 'Interior MEP Scans in Occupied Buildings',
                'excerpt' => 'Night shifts, escorts, and HVAC rules change scan efficiency more than equipment choice.',
                'cover' => 'https://images.unsplash.com/photo-1581094794329-c811d9fab50c?auto=format&fit=crop&w=1400&q=80',
                'body' => "Healthcare and education retrofits rarely offer empty rooms. Pointforge plans scan paths around patient flows, lockout windows, and ceiling access. Tell us about shutdown requirements when you book so field time stays productive.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'Misaligned point cloud in Revit', 'body' => "1. Confirm project units and shared coordinates.\n2. Re-link the .rcp from the latest registration folder.\n3. Verify control report against site targets.\n4. Contact Pointforge with job number if a re-export is needed."],
            ['category' => 'issues', 'title' => 'Scan positions blocked by occupancy', 'body' => "Reschedule for night/weekend windows or split into phased zones. Update the appointment in ERP and log surcharge SKUs if after-hours mobilization applies."],
            ['category' => 'guides', 'title' => 'Scan day field timeline', 'body' => "Day 0: Control layout and access walk.\nMorning: Perimeter / shell positions.\nMidday: Interior or mezzanine passes.\nAfternoon: Color imagery and target checks.\nCloseout: Field notes uploaded to project folder."],
            ['category' => 'guides', 'title' => 'How to prep a site for laser scanning', 'body' => "Clear trip hazards in scan paths, provide escorts for restricted areas, mark areas that cannot be captured, and share BIM or PDF plans for scan planning."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.\n1. Add customers in CRM.\n2. Create a scan job with GSF and quoted scan days.\n3. Schedule field appointments.\n4. Convert completed jobs to invoices and record payments.\n5. Use AI Studio for blog drafts and troubleshooting suggestions.\n6. Update company Settings for phone, WhatsApp, and tax rate."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.\nManager: jobs, products, invoices, scheduling.\nStaff: field appointments and job notes.\nAssign roles on the Users resource."],
            ['category' => 'troubleshoot', 'title' => 'Job running over quoted scan days', 'body' => "Review field notes for access delays or added zones. Log extra scan-day line items, update sq_ft notes, and issue a change-order invoice before final deliverable export."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Demo mode is expected without API keys. Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache. The public chatbot and AI Studio will prefer live models when keys are present."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
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
                'phone' => '(303) 555-0100',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@peakloadhauling.com'],
            [
                'name' => 'Sam Rivera',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(303) 555-0101',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'dispatch@peakloadhauling.com'],
            [
                'name' => 'Nate Brooks',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(303) 555-0102',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'Peakload Hauling Co.',
            'company_phone' => '(303) 555-0172',
            'company_email' => 'hello@peakloadhauling.com',
            'whatsapp' => '13035550172',
            'service_area' => 'Denver, Aurora, Boulder, Colorado Springs & Front Range',
            'tax_rate' => '8.31',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Tri-Axle Dirt Haul (load)', 'sku' => 'HAUL-DIRT-L', 'category' => 'haul', 'unit_cost' => 95, 'unit_price' => 185, 'stock_qty' => 40, 'unit' => 'load'],
            ['name' => 'End-Dump Spoil Haul (ton)', 'sku' => 'HAUL-SPL-T', 'category' => 'haul', 'unit_cost' => 12, 'unit_price' => 28, 'stock_qty' => 500, 'unit' => 'ton'],
            ['name' => 'Class 6 Road Base (ton)', 'sku' => 'AGG-C6-T', 'category' => 'aggregate', 'unit_cost' => 18, 'unit_price' => 36, 'stock_qty' => 420, 'unit' => 'ton'],
            ['name' => '3/4" Clean Rock (ton)', 'sku' => 'AGG-34-T', 'category' => 'aggregate', 'unit_cost' => 22, 'unit_price' => 42, 'stock_qty' => 310, 'unit' => 'ton'],
            ['name' => 'Concrete Dump Fee Pass-Through', 'sku' => 'DUMP-CON-T', 'category' => 'dump', 'unit_cost' => 8, 'unit_price' => 12, 'stock_qty' => 999, 'unit' => 'ton'],
            ['name' => 'Clean Dirt Dump Fee', 'sku' => 'DUMP-DIRT-L', 'category' => 'dump', 'unit_cost' => 25, 'unit_price' => 45, 'stock_qty' => 999, 'unit' => 'load'],
            ['name' => 'Standby / Wait Time (hour)', 'sku' => 'WAIT-HR', 'category' => 'surcharge', 'unit_cost' => 40, 'unit_price' => 95, 'stock_qty' => 80, 'unit' => 'hour'],
            ['name' => 'Fuel Surcharge (load)', 'sku' => 'FUEL-SUR-L', 'category' => 'surcharge', 'unit_cost' => 8, 'unit_price' => 18, 'stock_qty' => 200, 'unit' => 'load'],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['sku' => $product['sku']],
                $product + ['is_active' => true]
            );
        }

        $customers = collect([
            ['name' => 'Allison Kent', 'email' => 'allison@example.com', 'phone' => '(303) 555-2201', 'company' => null, 'city' => 'Denver', 'state' => 'CO', 'zip' => '80205', 'address' => '812 Larimer St'],
            ['name' => 'Derek Cho', 'email' => 'derek@frontedgesite.com', 'phone' => '(720) 555-2210', 'company' => 'Front Edge Siteworks', 'city' => 'Aurora', 'state' => 'CO', 'zip' => '80012', 'address' => '4500 Peoria St'],
            ['name' => 'Priya Shah', 'email' => 'ops@rockymtnbuild.com', 'phone' => '(303) 555-2299', 'company' => 'Rocky Mtn Builders', 'city' => 'Boulder', 'state' => 'CO', 'zip' => '80301', 'address' => '2100 Pearl Pkwy'],
            ['name' => 'Marcus Lee', 'email' => 'marcus.lee@example.com', 'phone' => '(719) 555-2188', 'company' => null, 'city' => 'Colorado Springs', 'state' => 'CO', 'zip' => '80903', 'address' => '119 Cascade Ave'],
            ['name' => 'Helen Ortiz', 'email' => 'helen@plainsviewhoa.org', 'phone' => '(303) 555-2177', 'company' => 'Plainsview HOA', 'city' => 'Lakewood', 'state' => 'CO', 'zip' => '80226', 'address' => '88 Wadsworth Blvd'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'Basement dig dirt export', 'service_type' => 'dirt_fill', 'status' => 'scheduled', 'sq_ft' => 48, 'quoted_amount' => 4200],
            ['customer' => 1, 'title' => 'Pad road-base delivery', 'service_type' => 'aggregate', 'status' => 'in_progress', 'sq_ft' => 220, 'quoted_amount' => 7800],
            ['customer' => 2, 'title' => 'Utility trench spoil haul', 'service_type' => 'spoil', 'status' => 'quoted', 'sq_ft' => 90, 'quoted_amount' => 5400],
            ['customer' => 3, 'title' => 'Garage demo debris haul', 'service_type' => 'demo', 'status' => 'completed', 'sq_ft' => 18, 'quoted_amount' => 2100, 'final_amount' => 2350],
            ['customer' => 4, 'title' => 'HOA playground rock delivery', 'service_type' => 'aggregate', 'status' => 'invoiced', 'sq_ft' => 36, 'quoted_amount' => 3200, 'final_amount' => 3150],
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
                    'notes' => 'Seeded demo job for Peakload ERP. sq_ft field stores tons/loads for haul tickets.',
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
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * 0.0831, 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * 1.0831, 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * 1.0831, 2) : 0,
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
                        'method' => 'card',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Basement dig haul — Kent'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'measure',
                'starts_at' => now()->addDays(2)->setTime(7, 0),
                'ends_at' => now()->addDays(2)->setTime(8, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'Road-base delivery day 1'],
            [
                'customer_id' => $customers[1]->id,
                'project_id' => $projectModels[1]->id,
                'type' => 'install',
                'starts_at' => now()->addDays(5)->setTime(6, 30),
                'ends_at' => now()->addDays(5)->setTime(15, 30),
                'status' => 'confirmed',
                'location' => $customers[1]->address,
            ]
        );

        $posts = [
            [
                'title' => 'How Dump Tickets Keep Dirt Haul Budgets Honest',
                'excerpt' => 'Weighed tickets beat guessed loads — here is how Peakload documents every haul for GCs.',
                'cover' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1400&q=80',
                'body' => "Dirt looks the same in a bed until you hit the scale. Peakload logs dump tickets against each job so Front Range GCs can reconcile tonnage against the estimate.\n\nAsk for ticket copies with your weekly invoice packet. If a load waits at the gate, the standby note lands on the same ticket trail.",
            ],
            [
                'title' => 'End Dump vs Tri-Axle: Matching Truck to Site Access',
                'excerpt' => 'Tight pads and soft site roads change which truck finishes the cycle fastest.',
                'cover' => 'https://images.unsplash.com/photo-1581094271901-8022df4466f9?auto=format&fit=crop&w=1400&q=80',
                'body' => "Tri-axles move more rock per trip when roads allow. End dumps shine when you need a controlled tip or tighter approach. Peakload sizes the truck after we hear about gate width, overhead lines, and dump preference — not from a one-line text.",
            ],
            [
                'title' => 'Ordering Road Base Before the Pad Crew Arrives',
                'excerpt' => 'Material delivery timing is as important as the tonnage itself.',
                'cover' => 'https://images.unsplash.com/photo-1590496793929-36417d3117de?auto=format&fit=crop&w=1400&q=80',
                'body' => "Dropping Class 6 before the grader is ready creates double handling. Peakload stages aggregate deliveries to the pad schedule so loaders stay productive and stockpiles stay clean. Share your pour or grade window when you book.",
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
            ['category' => 'issues', 'title' => 'Loader waiting on trucks', 'body' => "1. Confirm truck ETA and dump cycle time.\n2. Add a second truck if haul distance exceeds 20 minutes one-way.\n3. Log standby if the delay was gate or dump related.\n4. Update the job notes so the invoice reflects actual loads."],
            ['category' => 'issues', 'title' => 'Wrong dump facility for material', 'body' => "Verify soil/debris type before dispatch. Clean dirt, concrete, asphalt millings, and mixed C&D often require different facilities. Peakload confirms dump rules when the job is booked."],
            ['category' => 'guides', 'title' => 'Dirt haul day timeline', 'body' => "Day 0: Confirm access, dump ticket, and truck size.\nMorning: First truck on site at loader ready time.\nMidday: Rotate cycles; watch wait time.\nAfternoon: Final ticket photos and yard return.\nCloseout: Loads reconciled to invoice."],
            ['category' => 'guides', 'title' => 'How to prep a site for dump trucks', 'body' => "Clear overhead hazards, mark soft ground, keep a turnaround, and stage the loader. Share gate codes and dump preference the night before dispatch."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.\n1. Add customers in CRM.\n2. Create a Job/Project with tons/loads and quote.\n3. Schedule appointments for site checks and haul days.\n4. Convert completed jobs to invoices and record payments.\n5. Use AI Studio for blog drafts and troubleshooting suggestions.\n6. Update company Settings for phone, WhatsApp, and tax rate."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.\nManager: jobs, inventory, invoices, scheduling.\nStaff: field-facing records and appointment updates.\nAssign roles on the Users resource."],
            ['category' => 'troubleshoot', 'title' => 'Job running over tonnage estimate', 'body' => "Check actual loads vs quote. Confirm extra spoil from soft dig or wet dirt. Log additional tons on the job notes, create a change-order invoice line, and update inventory stock quantities."],
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
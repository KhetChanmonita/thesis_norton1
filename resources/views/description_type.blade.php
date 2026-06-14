<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($type === 'import-shv') នាំចូលពីកំពង់ផែស្វ័យយ័តព្រះសីហនុ
        @elseif($type === 'export-shv') នាំចេញទៅកំពង់ផែស្វ័យយ័តព្រះសីហនុ
        @elseif($type === 'import-pp') នាំចូលពីកំពង់ផែស្វ័យយ័តភ្នំពេញ
        @else នាំចេញទៅកំពង់ផែស្វ័យយ័តភ្នំពេញ
        @endif
        | LS Trucking Service
    </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/description_type.css') }}">
    <style>
        .dt-hero {
            background:
                linear-gradient(135deg, rgba(255,107,0,0.90) 0%, rgba(230,75,0,0.85) 100%),
                url('{{ $type === "import-shv" || $type === "export-shv"
                    ? asset("images/import-shv.png")
                    : asset("images/Import-pp.png") }}') center/cover no-repeat;
        }
    </style>
</head>
<body>

@include('partials.header')

@php
/* ── Data per service type ── */
$services = [
    'import-shv' => [
        'number'   => '01',
        'badge'    => 'នាំចូល — SHV',
        'badge_icon' => 'fas fa-file-import',
        'title'    => 'Import From <span>SHV Port</span>',
        'subtitle' => 'ដឹកជញ្ជូននាំចូលពីកំពង់ផែស្វ័យយ័តព្រះសីហនុ',
        'hero_desc'=> 'សេវាកម្មដឹកជញ្ជូនទំនិញនាំចូលពីកំពង់ផែស្វ័យយ័តព្រះសីហនុ (Sihanoukville Autonomous Port) ទៅរោងចក្រ ឬគោលដៅក្នុងប្រទេស ដោយប្រើប្រាស់ដំណោះស្រាយដឹកជញ្ជូនប្រកបដោយវិជ្ជាជីវៈ រហ័ស និងមានសុវត្ថិភាព។',
        'stats' => [
            ['val' => '1-3', 'lbl' => 'ថ្ងៃដឹកជញ្ជូន'],
            ['val' => '25', 'lbl' => 'ខេត្ត-ក្រុងគ្របដណ្តប់'],
            ['val' => '98%', 'lbl' => 'ការដឹកទាន់ពេល'],
        ],
        'definition' => [
            'ការ<strong>នាំចូល (Import)</strong> គឺជាដំណើរការដឹកជញ្ជូនទំនិញដែលបានបញ្ចូលមកពីប្រទេសក្រៅ ឆ្លងកាត់ការត្រួតពិនិត្យរបស់ (Customs) ហើយធ្វើការដឹកជញ្ជូនទៅកាន់ទីកន្លែងផ្ទុករបស់អតិថិជន ដូចជា រោងចក្រ ឃ្លាំង ឬផ្ទះទំនើរ។',
            'កំពង់ផែស្វ័យយ័តព្រះសីហនុ គឺជាកំពង់ផែធំបំផុតក្នុងប្រទេសកម្ពុជា ដែលទទួលទំនិញពាណិជ្ជកម្មអន្តរជាតិ ដោយសារទំហំ និងបរិមាណទំនិញចូលច្រើន ជារៀងរាល់ថ្ងៃ ក្រុមហ៊ុនយើងផ្ដល់សេវាដឹកជញ្ជូនបន្ទាប់ (Last Mile Delivery) ពីកំពង់ផែទៅដល់ទីតាំងអ្នក។',
        ],
        'steps' => [
            ['num'=>'01','title'=>'ទំនាក់ទំនងតាមទូរស័ព្ទ ឬអ៊ីម៉ែល','desc'=>'ផ្ញើព័ត៌មានអំពីទំនិញ លេខកុងតឺន័រ និងទីតាំងដឹកទៅ'],
            ['num'=>'02','title'=>'ផ្ដល់ការដកស្រង់តម្លៃ','desc'=>'ក្រុមការងារនឹងផ្ដល់ Price Quotation ក្នុងរយៈពេល ២-៤ ម៉ោង'],
            ['num'=>'03','title'=>'ចុះកិច្ចព្រមព្រៀង','desc'=>'ចុះហត្ថលេខាលើឯកសារ Booking Confirmation'],
            ['num'=>'04','title'=>'ទទួលកុងតឺន័រពីកំពង់ផែ','desc'=>'ក្រុមរបស់យើងទៅដឹកកុងតឺន័រពី SHV Port ដោយផ្ទាល់'],
            ['num'=>'05','title'=>'ដឹកជញ្ជូនទៅទីតាំង','desc'=>'ដឹកជញ្ជូនទំនិញទៅដល់ទីតាំងអ្នក ជូនដំណឹងតាម GPS'],
            ['num'=>'06','title'=>'ដល់រោងចក្រ ឬឃ្លាំង និងបញ្ចុះទំនិញ','desc'=>'អ្នកបើកបរសហការជាមួយអ្នកគ្រប់គ្រងទីតាំង ដើម្បីបញ្ចុះ និងផ្ទៀងផ្ទាត់ទំនិញឲ្យត្រូវតាមបញ្ជី មុនប្រគល់ជូនអតិថិជន'],
            ['num'=>'07','title'=>'ការដឹកបានបញ្ចប់','desc'=>'ផ្ញើរបាយការណ៍ Delivery Confirmation និងរូបថត POD'],
        ],
        'features' => [
            'ដឹកជញ្ជូនគ្រប់ប្រភេទទំនិញ រួមមានទំនិញឧស្សាហកម្ម ទំនិញទូទៅ និងទំនិញពិសេស',
            'ដំណោះស្រាយឯកសារពន្ធដារ (Customs Clearance) ដោយអ្នកជំនាញ',
            'ប្រព័ន្ធតាមដានជូន GPS ដោយផ្ទាល់ Real-Time',
            'ធានារ៉ាប់រងទំនិញ (Cargo Insurance) ពេញដំណើរ',
            'ឆ្លើយតបសំណួរ ២៤ ម៉ោង ៧ ថ្ងៃ/សប្ដាហ៍',
            'គ្របដណ្តប់ ២៥ ខេត្ត-ក្រុងទូទាំងប្រទេស',
        ],
        'docs' => [
            ['icon'=>'fas fa-file-alt','name'=>'Bill of Lading (B/L)'],
            ['icon'=>'fas fa-file-invoice','name'=>'Commercial Invoice'],
            ['icon'=>'fas fa-list','name'=>'Packing List'],
            ['icon'=>'fas fa-certificate','name'=>'Certificate of Origin'],
            ['icon'=>'fas fa-file-contract','name'=>'Import License (ប្រសិនបើចាំបាច់)'],
            ['icon'=>'fas fa-clipboard-check','name'=>'Customs Declaration Form'],
        ],
        'info' => [
            ['lbl'=>'ពេលវេលា','val'=>'១-៣ ថ្ងៃការងារ','orange'=>true],
            ['lbl'=>'ប្រភពកំពង់ផែ','val'=>'SHV Port','orange'=>false],
            ['lbl'=>'គ្របដណ្តប់','val'=>'ទូទាំងប្រទេស','orange'=>false],
            ['lbl'=>'ប្រភេទរថយន្ត','val'=>'Fuso ១២-១៥ តោន','orange'=>false],
            ['lbl'=>'ការតាមដាន','val'=>'GPS Real-Time','orange'=>true],
        ],
    ],

    'export-shv' => [
        'number'   => '02',
        'badge'    => 'នាំចេញ — SHV',
        'badge_icon' => 'fas fa-file-export',
        'title'    => 'Export To <span>SHV Port</span>',
        'subtitle' => 'ដឹកជញ្ជូននាំចេញទៅកំពង់ផែស្វ័យយ័តព្រះសីហនុ',
        'hero_desc'=> 'សេវាកម្មដឹកជញ្ជូនទំនិញនាំចេញពីរោងចក្រ ឃ្លាំង ឬទីតាំងអ្នកក្នុងប្រទេស ទៅកំពង់ផែស្វ័យយ័តព្រះសីហនុ (SHV Port) ដើម្បីនាំចេញទៅប្រទេសក្រៅ ដោយប្រើប្រាស់ក្រុមអ្នកជំនាញ និងវិធីសាស្ត្រដ៏ប្រកបដោយសុវត្ថិភាព។',
        'stats' => [
            ['val' => '2-4', 'lbl' => 'ថ្ងៃដឹកជញ្ជូន'],
            ['val' => '25', 'lbl' => 'ខេត្ត-ក្រុង'],
            ['val' => '98%', 'lbl' => 'ការដឹកទាន់ពេល'],
        ],
        'definition' => [
            'ការ<strong>នាំចេញ (Export)</strong> គឺជាដំណើរការដឹកជញ្ជូនទំនិញពីក្នុងប្រទេស ទៅចេញខាងក្រៅ ដោយឆ្លងកាត់ការត្រួតពិនិត្យ Customs ហើយដឹកជញ្ជូនទៅដល់កំពង់ផែ ដើម្បីផ្ទុករំលើក ឬដឹកតាមសមុទ្រ។',
            'ក្រុមហ៊ុនយើងផ្ដល់សេវាផ្ទុកកុងតឺន័រ (Container Stuffing) ត្រឹមត្រូវ ហើយដឹកទៅ SHV Port ទាន់ Cut-Off Time ដោយរក្សាលក្ខណៈបច្ចេកទេសទំនិញ (ប្រចំ Temperature, Humidity) ជានិច្ច។',
        ],
        'steps' => [
            ['num'=>'01','title'=>'ស្នើសុំការដឹកនាំចេញ','desc'=>'ផ្ញើ Booking Request ជាមួយ ETA ទូករបស់អ្នក'],
            ['num'=>'02','title'=>'រៀបចំ Container Stuffing','desc'=>'ក្រុមការងារអញ្ជើញទៅ Load ទំនិញត្រឹមត្រូវតាមស្ដង់ដារ'],
            ['num'=>'03','title'=>'ផ្ញើ Pre-Advice ពន្ធដារ','desc'=>'ដំណើរការ Export Declaration Form ជំនួសអ្នក'],
            ['num'=>'04','title'=>'ដឹកជញ្ជូនទៅ SHV Port','desc'=>'ដឹកកុងតឺន័រចេញពីរោងចក្រ ទៅ SHV ដោយ GPS Tracking'],
            ['num'=>'05','title'=>'ចុះលេខ Gate In','desc'=>'ចូល Gate ប្រគល់ Seal Container ហើយ Gate In ទាន់ Cut-Off'],
            ['num'=>'06','title'=>'ផ្ញើ POD Document','desc'=>'ផ្ញើ Delivery Confirmation និង Gate-In Receipt ជូនអ្នក'],
        ],
        'features' => [
            'ដំណោះស្រាយ Container Stuffing ត្រឹមត្រូវ ប្រកបដោយស្ដង់ដារ',
            'ដំណើរការ Export Customs Declaration ជំនួសអ្នក',
            'ធានានាំចូល Gate-In ទាន់ Cut-Off Time',
            'ដឹកជញ្ជូនគ្រប់ប្រភេទកុងតឺន័រ 20\', 40\', 45\'',
            'ការតាមដានជូន GPS ពេញដំណើរ',
            'ធានារ៉ាប់រងទំនិញ Cargo Insurance',
        ],
        'docs' => [
            ['icon'=>'fas fa-file-alt','name'=>'Packing List'],
            ['icon'=>'fas fa-file-invoice','name'=>'Commercial Invoice'],
            ['icon'=>'fas fa-certificate','name'=>'Certificate of Origin'],
            ['icon'=>'fas fa-clipboard-list','name'=>'Export License'],
            ['icon'=>'fas fa-file-contract','name'=>'Booking Confirmation'],
            ['icon'=>'fas fa-check-circle','name'=>'Customs Export Form'],
        ],
        'info' => [
            ['lbl'=>'ពេលវេលា','val'=>'២-៤ ថ្ងៃការងារ','orange'=>true],
            ['lbl'=>'គោលដៅ','val'=>'SHV Port','orange'=>false],
            ['lbl'=>'គ្របដណ្តប់','val'=>'ទូទាំងប្រទេស','orange'=>false],
            ['lbl'=>'ប្រភេទ Container','val'=>'20\' / 40\' / 45\'','orange'=>false],
            ['lbl'=>'Cut-Off','val'=>'ធានាទាន់ Cut-Off','orange'=>true],
        ],
    ],

    'import-pp' => [
        'number'   => '03',
        'badge'    => 'នាំចូល — PP',
        'badge_icon' => 'fas fa-file-import',
        'title'    => 'Import From <span>PP Port</span>',
        'subtitle' => 'ដឹកជញ្ជូននាំចូលពីកំពង់ផែស្វ័យយ័តភ្នំពេញ',
        'hero_desc'=> 'សេវាកម្មដឹកជញ្ជូនទំនិញនាំចូលពីកំពង់ផែស្វ័យយ័តអន្តរជាតិភ្នំពេញ (PP Port) ទៅរោងចក្រ ឃ្លាំង ឬគោលដៅ ក្នុងតំបន់ភ្នំពេញ និងខេត្ត-ក្រុងជុំវិញ ដោយប្រើប្រាស់ LD (Local Distribution) System ដ៏មានប្រសិទ្ធភាព។',
        'stats' => [
            ['val' => '1-2', 'lbl' => 'ថ្ងៃដឹកជញ្ជូន'],
            ['val' => '25', 'lbl' => 'ខេត្ត-ក្រុងគ្របដណ្តប់'],
            ['val' => '98%', 'lbl' => 'ការដឹកទាន់ពេល'],
        ],
        'definition' => [
            'កំពង់ផែស្វ័យយ័តអន្តរជាតិភ្នំពេញ (Phnom Penh Autonomous Port) គឺជាច្រករបៀងពាណិជ្ជកម្មសំខាន់ ដែលទទួលទំនិញខ្នាតតូច ទំនិញ Inland Container Depot (ICD) ជាច្រើន ដែលបញ្ចូនទំនិញតាមផ្លូវទឹក (River) ពីប្រទេសវៀតណាម ចូលមកកម្ពុជា។',
            'ការ<strong>នាំចូល (Import)</strong> តាម PP Port ច្រើនជំរើសសម្រាប់ក្រុមហ៊ុននៅក្នុងរាជធានីភ្នំពេញ ខេត្តកណ្ដាល តាកែវ និងខេត្ដជិតៗ ព្រោះតម្លៃ Inland Transport ទាប និងចំណាយពេលល្ហ។',
        ],
        'steps' => [
            ['num'=>'01','title'=>'ទំនាក់ទំនង និងផ្ដល់ Booking','desc'=>'ផ្ញើ Booking Request ព័ត៌មានអ្នក ជាមួយ ETA Container'],
            ['num'=>'02','title'=>'ផ្ដល់ Price Quotation','desc'=>'ក្រុមការងារផ្ដល់ Quotation លម្អិត ២-៤ ម៉ោងបន្ទាប់'],
            ['num'=>'03','title'=>'ដំណើរការ Customs Clearance','desc'=>'ក្រុមការងារ Customs ទៅ PP Port ដំណើរការឯកសារ'],
            ['num'=>'04','title'=>'ដឹកកុងតឺន័រចេញ PP Port','desc'=>'ទទួល Container ចេញ PP Port Gate និងត្រូតពិនិត្យ Seal'],
            ['num'=>'05','title'=>'ដឹកដល់ទីតាំង','desc'=>'ដឹករហ័ស ជូនដំណឹងតាម GPS ២-៣ ម៉ោងបន្ទាប់'],
            ['num'=>'06','title'=>'ដល់រោងចក្រ ឬឃ្លាំង និងបញ្ចុះទំនិញ','desc'=>'អ្នកបើកបរសហការជាមួយអ្នកគ្រប់គ្រងទីតាំង ដើម្បីបញ្ចុះ និងផ្ទៀងផ្ទាត់ទំនិញឲ្យត្រូវតាមបញ្ជី មុនប្រគល់ជូនអតិថិជន'],
            ['num'=>'07','title'=>'Delivery Completed','desc'=>'ផ្ញើ POD ជូន និង Empty Return Container'],
        ],
        'features' => [
            'ពេលវេលាដឹកជញ្ជូន ១-២ ថ្ងៃ (លឿនជាង SHV)',
            'ចំណាយ Inland Transport ទាបសម្រាប់អ្នកដែលនៅ PP',
            'Customs Clearance ដោយបុគ្គលិកអ្នកជំនាញ',
            'GPS Tracking Real-Time ពេញដំណើរ',
            'Empty Return Container ត្រឡប់ Port ដោយផ្ទាល់',
            'ធានារ៉ាប់រង Cargo Insurance',
        ],
        'docs' => [
            ['icon'=>'fas fa-file-alt','name'=>'Bill of Lading (B/L)'],
            ['icon'=>'fas fa-file-invoice','name'=>'Commercial Invoice'],
            ['icon'=>'fas fa-list','name'=>'Packing List'],
            ['icon'=>'fas fa-certificate','name'=>'Certificate of Origin'],
            ['icon'=>'fas fa-file-contract','name'=>'Import Permit'],
            ['icon'=>'fas fa-clipboard-check','name'=>'Customs Declaration'],
        ],
        'info' => [
            ['lbl'=>'ពេលវេលា','val'=>'១-២ ថ្ងៃការងារ','orange'=>true],
            ['lbl'=>'ប្រភពកំពង់ផែ','val'=>'PP Port','orange'=>false],
            ['lbl'=>'គ្របដណ្តប់','val'=>'ភ្នំពេញ + ខេត្ត','orange'=>false],
            ['lbl'=>'ប្រភេទរថយន្ត','val'=>'Fuso ១២-១៥ តោន','orange'=>false],
            ['lbl'=>'ការតាមដាន','val'=>'GPS Real-Time','orange'=>true],
        ],
    ],

    'export-pp' => [
        'number'   => '04',
        'badge'    => 'នាំចេញ — PP',
        'badge_icon' => 'fas fa-file-export',
        'title'    => 'Export To <span>PP Port</span>',
        'subtitle' => 'ដឹកជញ្ជូននាំចេញទៅកំពង់ផែស្វ័យយ័តភ្នំពេញ',
        'hero_desc'=> 'សេវាកម្មដឹកជញ្ជូនទំនិញនាំចេញពីរោងចក្រ ឃ្លាំង ឬទីតាំងក្នុងប្រទេស ទៅកំពង់ផែស្វ័យយ័តអន្តរជាតិភ្នំពេញ (PP Port) ដោយប្រើប្រាស់ដំណោះស្រាយដឹកជញ្ជូនលឿន ទៀងទាត់ ហើយអាចជឿជាក់បានសម្រាប់ទំនិញ Export ទៅប្រទេសក្រៅ។',
        'stats' => [
            ['val' => '1-3', 'lbl' => 'ថ្ងៃដឹកជញ្ជូន'],
            ['val' => '25', 'lbl' => 'ខេត្ត-ក្រុង'],
            ['val' => '98%', 'lbl' => 'ទំនាន់ Cut-Off'],
        ],
        'definition' => [
            'ការ<strong>នាំចេញ (Export)</strong> តាម PP Port ជាជម្រើសសំខាន់ ជាពិសេសសម្រាប់ក្រុមហ៊ុន ឧស្សាហកម្ម និងកសិកម្ម ដែលនៅខេត្តកណ្ដាល តាកែវ ព្រៃវែង ហើយដឹកទំនិញ Export ទៅប្រទេសវៀតណាម ឬបន្ដតាម Ocean Freight ។',
            'PP Port ក៏ជា ICD (Inland Container Depot) ដ៏ធំ ដែលទទួល Container Feeder Ship ពី Vietnam មកចរើន ដូច្នេះ ក្រុមហ៊ុន Export ច្រើនប្រើ PP Port ជំនួស SHV Port ក្នុងករណីជ្រើសតម្លៃ Freight ទាប ឬ Transit Time ឆាប់ជាង។',
        ],
        'steps' => [
            ['num'=>'01','title'=>'ស្នើសុំ Export Booking','desc'=>'ផ្ញើ ETA, Cut-Off Date ព័ត៌មានទំនិញ'],
            ['num'=>'02','title'=>'Container Positioning','desc'=>'ក្រុមការងារ Position Empty Container ទៅ Loading Point'],
            ['num'=>'03','title'=>'Loading ទំនិញ','desc'=>'ភ្ជាប់ Container Seal បន្ទាប់ពី Load ត្រឹមត្រូវ'],
            ['num'=>'04','title'=>'Export Customs','desc'=>'ដំណើរការ Export Declaration Form ជំនួសអ្នក'],
            ['num'=>'05','title'=>'ដឹកទៅ PP Port','desc'=>'ដឹក Container ទៅ PP Port Gate In ទាន់ Cut-Off'],
            ['num'=>'06','title'=>'Gate In Confirmation','desc'=>'ផ្ញើ Gate-In Receipt, EIR ជូនភ្លាម'],
        ],
        'features' => [
            'Container Positioning ដល់ Loading Point ចាំ Load',
            'Seal Container ដោយ Official Customs Seal',
            'ធានា Gate-In ទាន់ Cut-Off Time',
            'Export Customs Declaration ជំនួស',
            'GPS Tracking ពេញដំណើរ',
            'ផ្ញើ EIR (Equipment Interchange Receipt) ភ្លាម',
        ],
        'docs' => [
            ['icon'=>'fas fa-file-alt','name'=>'Packing List'],
            ['icon'=>'fas fa-file-invoice','name'=>'Commercial Invoice'],
            ['icon'=>'fas fa-certificate','name'=>'Certificate of Origin'],
            ['icon'=>'fas fa-clipboard-list','name'=>'Export License'],
            ['icon'=>'fas fa-file-contract','name'=>'Booking Confirmation'],
            ['icon'=>'fas fa-stamp','name'=>'Phytosanitary Cert (ប្រសិនបើ Agri)'],
        ],
        'info' => [
            ['lbl'=>'ពេលវេលា','val'=>'១-៣ ថ្ងៃការងារ','orange'=>true],
            ['lbl'=>'គោលដៅ','val'=>'PP Port','orange'=>false],
            ['lbl'=>'គ្របដណ្តប់','val'=>'ទូទាំងប្រទេស','orange'=>false],
            ['lbl'=>'ប្រភេទ Container','val'=>'20\' / 40\' / 45\'','orange'=>false],
            ['lbl'=>'Cut-Off','val'=>'ធានាទាន់ Cut-Off','orange'=>true],
        ],
    ],
];

$s = $services[$type] ?? $services['import-shv'];
@endphp

{{-- ── Hero ── --}}
<section class="dt-hero">
    <div class="dt-container dt-hero-inner">
        <div class="dt-breadcrumb">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> ទំព័រដើម</a>
            <i class="fas fa-chevron-right" style="font-size:.7rem;"></i>
            <a href="{{ route('home') }}#services">សេវាកម្ម</a>
            <i class="fas fa-chevron-right" style="font-size:.7rem;"></i>
            <span>{{ $s['subtitle'] }}</span>
        </div>

        <div class="dt-badge">
            <i class="{{ $s['badge_icon'] }}"></i>
            {{ $s['badge'] }} &nbsp;·&nbsp; Service {{ $s['number'] }}
        </div>

        <h1>{!! $s['title'] !!}</h1>
        <p>{{ $s['hero_desc'] }}</p>

        <div class="dt-hero-stats">
            @foreach($s['stats'] as $stat)
                <div class="dt-hero-stat">
                    <span class="val">{{ $stat['val'] }}</span>
                    <span class="lbl">{{ $stat['lbl'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Body ── --}}
<div class="dt-body">
    <div class="dt-container">
        <div class="dt-grid">

            {{-- Left column --}}
            <div>
                {{-- Definition --}}
                <div class="dt-card">
                    <div class="dt-card-header">
                        <i class="fas fa-book-open"></i>
                        <h2>និយមន័យ និងការពន្យល់</h2>
                    </div>
                    <div class="dt-card-body">
                        @foreach($s['definition'] as $para)
                            <p>{!! $para !!}</p>
                        @endforeach
                    </div>
                </div>

                {{-- Process --}}
                <div class="dt-card">
                    <div class="dt-card-header">
                        <i class="fas fa-list-ol"></i>
                        <h2>ដំណើរការធ្វើការ (Step by Step)</h2>
                    </div>
                    <div class="dt-card-body">
                        <div class="dt-steps">
                            @foreach($s['steps'] as $step)
                                <div class="dt-step">
                                    <div class="dt-step-num">{{ $step['num'] }}</div>
                                    <div class="dt-step-content">
                                        <h4>{{ $step['title'] }}</h4>
                                        <p>{{ $step['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <div class="dt-card">
                    <div class="dt-card-header">
                        <i class="fas fa-star"></i>
                        <h2>អត្ថប្រយោជន៍ និងលក្ខណៈពិសេស</h2>
                    </div>
                    <div class="dt-card-body">
                        <ul class="dt-features">
                            @foreach($s['features'] as $f)
                                <li><i class="fas fa-check-circle"></i> {{ $f }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Documents --}}
                <div class="dt-card">
                    <div class="dt-card-header">
                        <i class="fas fa-folder-open"></i>
                        <h2>ឯកសារដែលត្រូវការ</h2>
                    </div>
                    <div class="dt-card-body">
                        <div class="dt-docs">
                            @foreach($s['docs'] as $doc)
                                <div class="dt-doc">
                                    <i class="{{ $doc['icon'] }}"></i>
                                    {{ $doc['name'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="dt-sidebar">

                {{-- Quick Info --}}
                <div class="dt-sidebar-card">
                    <div class="dt-sidebar-header">
                        <i class="fas fa-info-circle"></i> ព័ត៌មានសង្ខេប
                    </div>
                    <div class="dt-sidebar-body">
                        @foreach($s['info'] as $row)
                            <div class="dt-info-row">
                                <span class="lbl">{{ $row['lbl'] }}</span>
                                <span class="val {{ $row['orange'] ? 'orange' : '' }}">{{ $row['val'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- CTA --}}
                <div class="dt-cta-card">
                    <i class="fas fa-truck"></i>
                    <h3>ត្រៀមខ្លួនហើយ?</h3>
                    <p>ទំនាក់ទំនងយើងខ្ញុំ ឬដាក់ស្នើការកក់ភ្លាមៗ ក្រុមការងារត្រៀមខ្លួន ២៤/៧</p>
                    <a href="{{ route('trucks_section') }}" class="dt-cta-btn primary">
                        <i class="fas fa-calendar-check"></i> កក់សេវាឥឡូវ
                    </a>
                    <a href="{{ route('home') }}" class="dt-cta-btn secondary">
                        <i class="fas fa-arrow-left"></i> ត្រឡប់ទំព័រដើម
                    </a>
                </div>

                {{-- Other Services --}}
                <div class="dt-sidebar-card">
                    <div class="dt-sidebar-header">
                        <i class="fas fa-th-list"></i> សេវាផ្សេងទៀត
                    </div>
                    <div class="dt-sidebar-body" style="padding:8px 12px;">
                        @foreach(['import-shv'=>'01 Import From SHV','export-shv'=>'02 Export To SHV','import-pp'=>'03 Import From PP','export-pp'=>'04 Export To PP'] as $key=>$label)
                            <a href="{{ route('service.detail', $key) }}"
                               style="display:flex;align-items:center;gap:10px;padding:10px 8px;border-radius:8px;text-decoration:none;color:{{ $type===$key ? '#FF6B00' : '#444' }};font-weight:{{ $type===$key ? '700' : '500' }};font-size:.88rem;background:{{ $type===$key ? '#fff5ed' : 'transparent' }};transition:all .2s;margin-bottom:2px;">
                                <i class="fas {{ str_contains($key,'import') ? 'fa-file-import' : 'fa-file-export' }}" style="color:#FF6B00;width:16px;"></i>
                                {{ $label }}
                                @if($type===$key) <i class="fas fa-check" style="margin-left:auto;color:#FF6B00;font-size:.75rem;"></i> @endif
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('partials.footer')

</body>
</html>

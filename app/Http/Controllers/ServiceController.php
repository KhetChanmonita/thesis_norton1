<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = [
            [
                'id' => 1,
                'number' => '០១',
                'title' => 'នាំចូលពីកំពង់ផែព្រះសីហនុ',
                'description' => 'សេវាកម្មដឹកជញ្ជូនកុងតឺន័រដែលអាចទុកចិត្តបានពីកំពង់ផែទៅកាន់ទីតាំងគោលដៅ',
                'image' => 'images/import-shv.png',
                'badge' => 'នាំចូល',
                'icon' => 'fas fa-ship',
                'features' => [
                    'ដឹកជញ្ជូនកុងតឺន័រពេញលេញ',
                    'គ្រប់គ្រងឯកសារផ្លូវការ',
                    'តាមដានជូនដំណើរការជាពេលវេលាពិត'
                ],
                'color' => '#3498db'
            ],
            [
                'id' => 2,
                'number' => '០២',
                'title' => 'នាំចេញទៅកំពង់ផែព្រះសីហនុ',
                'description' => 'សេវាកម្មដឹកជញ្ជូននាំចេញអន្តរជាតិជាមួយនឹងលំហូរឡូជីខ្ទិករលូន និងមានសុវត្ថិភាព',
                'image' => 'images/export.png',
                'badge' => 'នាំចេញ',
                'icon' => 'fas fa-globe-asia',
                'features' => [
                    'ដោះស្រាយឯកសារផ្លូវការពេញលេញ',
                    'សេវាកម្មរហ័ស និងទាន់ពេល',
                    'គ្របដណ្តប់ទូទាំងពិភពលោក'
                ],
                'color' => '#e74c3c'
            ],
            // Add more services here
        ];

        $stats = [
            ['number' => '1000+', 'label' => 'ការដឹកជញ្ជូនជោគជ័យ'],
            ['number' => '50+', 'label' => 'រថយន្តសកម្ម'],
            ['number' => '98%', 'label' => 'អត្រាទាន់ពេល'],
            ['number' => '24/7', 'label' => 'សេវាកម្មគាំទ្រ']
        ];

        return view('services', compact('services', 'stats'));
    }
}
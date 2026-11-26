<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    /** name => [brand icon, brand colour] */
    private const TECHNOLOGIES = [
        'PHP' => ['php', '#777BB4'],
        'Laravel' => ['laravel', '#FF2D20'],
        'MySQL' => ['mysql', '#4479A1'],
        'HTML5' => ['html5', '#E34F26'],
        'CSS' => ['css', '#663399'],
        'JavaScript' => ['javascript', '#F7DF1E'],
        'TypeScript' => ['typescript', '#3178C6'],
        'Tailwind CSS' => ['tailwindcss', '#06B6D4'],
        'Bootstrap' => ['bootstrap', '#7952B3'],
        'Sass' => ['sass', '#CC6699'],
        'Alpine.js' => ['alpinedotjs', '#8BC0D0'],
        'Livewire' => ['livewire', '#4E56A6'],
        'Filament' => ['filament', '#FDAE4B'],
        'Vue.js' => ['vuedotjs', '#4FC08D'],
        'React' => ['react', '#61DAFB'],
        'Next.js' => ['nextdotjs', '#000000'],
        'Node.js' => ['nodedotjs', '#5FA04E'],
        'Vite' => ['vite', '#9135FF'],
        'WordPress' => ['wordpress', '#21759B'],
        'WooCommerce' => ['woocommerce', '#96588A'],
        'Shopify' => ['shopify', '#7AB55C'],
        'Flutter' => ['flutter', '#02569B'],
        'Redis' => ['redis', '#FF4438'],
        'Nginx' => ['nginx', '#009639'],
        'Docker' => ['docker', '#2496ED'],
        'Cloudflare' => ['cloudflare', '#F38020'],
        'Google Analytics' => ['googleanalytics', '#E37400'],
        'Figma' => ['figma', '#F24E1E'],
    ];

    /** The technologies a project can pick, with their logos. */
    public function run(): void
    {
        $order = 0;

        foreach (self::TECHNOLOGIES as $name => [$icon, $color]) {
            Technology::query()->firstOrCreate(['name' => $name], ['icon' => 'brand-'.$icon, 'color' => $color, 'order' => $order += 10]);
        }
    }
}

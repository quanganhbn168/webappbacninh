<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Slug;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Models\Post;
use App\Models\PostCategory;

class MigrateSlugsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slug:migrate {--refresh : Clear existing slugs before migrating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing slugs from models to the central slugs table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('refresh')) {
            if ($this->confirm('Are you sure you want to clear all existing centralized slugs?')) {
                Slug::truncate();
                $this->info('Cleared `slugs` table.');
            }
        }

        $models = [
            'Templates' => Template::class,
            'Template Categories' => TemplateCategory::class,
            'Posts' => Post::class,
            'Post Categories' => PostCategory::class,
        ];

        foreach ($models as $name => $class) {
            $this->info("Migrating {$name}...");
            
            $items = $class::whereNotNull('slug')->where('slug', '!=', '')->get();
            $bar = $this->output->createProgressBar($items->count());
            
            $count = 0;
            foreach ($items as $item) {
                // Check if slug already exists
                $exists = Slug::where('key', $item->slug)->exists();
                
                if ($exists) {
                     // Check if it belongs to the same item (idempotency)
                     $isSame = Slug::where('key', $item->slug)
                         ->where('reference_id', $item->id)
                         ->where('reference_type', $class)
                         ->exists();
                     
                     if (!$isSame) {
                         $this->error("\nDuplicate slug found: {$item->slug} (for {$class} ID {$item->id}). Skipping.");
                         continue;
                     }
                } else {
                    Slug::create([
                        'key' => $item->slug,
                        'reference_id' => $item->id,
                        'reference_type' => $class,
                    ]);
                    $count++;
                }
                
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            $this->info("Migrated {$count} {$name}.");
        }

        $this->info('Slug migration completed successfully!');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\Redirect;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChangeNewsSlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change_news_slug {old_slug} {new_slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $old_slug = $this->argument('old_slug');
        $new_slug = $this->argument('new_slug');
        
        $this->info($old_slug);
        $this->info($new_slug);

        if ($old_slug === $new_slug)
        {
            $err ='$old_slug and $new_slug must be different.';
            $this->newLine(1);
            $this->error(str_repeat(' ', strlen($err) + 4));
            $this->error('  ' . $err . '  ');
            $this->error(str_repeat(' ', strlen($err) + 4));
            $this->newLine(1);
            return 1;
        }

        $same_redir = Redirect::query()->where('old_slug', route('news_item', ['slug' => $old_slug]))
        ->where('new_slug', route('news_item', ['slug' => $new_slug])) 
        ->first();
        

        if($same_redir !== null) 
        {
            $err ='this redirect already exist.';
            $this->newLine(1);
            $this->error(str_repeat(' ', strlen($err) + 4));
            $this->error('  ' . $err . '  ');
            $this->error(str_repeat(' ', strlen($err) + 4));
            $this->newLine(1);
            return 1;
        }

        $news = News::query()->where('slug', $old_slug)->first();
        if ($news === null)
        {
            $err ='news was not found by $old_slug.';
            $this->newLine(1);
            $this->error(str_repeat(' ', strlen($err) + 4));
            $this->error('  ' . $err . '  ');
            $this->error(str_repeat(' ', strlen($err) + 4));
            $this->newLine(1);
            return 1;
        }

        DB::transaction(function() use ($news, $new_slug) 
        {
            Redirect::query()->where('old_slug', $new_slug)->delete();
            $news->slug = $new_slug;
            $news->save();
        });

        return Command::SUCCESS;
    }
}

<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\CmsPost;
use App\Models\KnowledgeBase;
use Database\Seeders\CmsPageSeeder;
use Database\Seeders\CmsPostSeeder;
use Database\Seeders\KnowledgeBaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsContentSeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_content_and_are_safe_to_rerun(): void
    {
        foreach ([CmsPageSeeder::class, CmsPostSeeder::class, KnowledgeBaseSeeder::class] as $seeder) {
            $this->seed($seeder);
            $this->seed($seeder);
        }

        $this->assertSame(6, CmsPage::count());
        $this->assertSame(6, CmsPage::where('show_in_footer', true)->count());
        $this->assertSame(6, CmsPost::count());
        $this->assertSame(10, KnowledgeBase::count());

        $page = CmsPage::first();
        $this->get(route('page.show', $page->slug))->assertOk()->assertSee($page->title);
        $this->get(route('blog.index'))->assertOk();
        $this->get(route('blog.show', CmsPost::first()->slug))->assertOk();
        $this->get(route('knowledge-base.index'))->assertOk();
        $this->get(route('knowledge-base.show', KnowledgeBase::first()->slug))->assertOk();
        $this->get(route('home'))->assertOk()->assertSee($page->title);
    }

    public function test_rerun_keeps_content_edited_in_the_cms(): void
    {
        $this->seed(CmsPageSeeder::class);
        CmsPage::where('slug', 'kontak')->update(['content' => '<p>Edit saya</p>']);
        $this->seed(CmsPageSeeder::class);

        $this->assertSame('<p>Edit saya</p>', CmsPage::where('slug', 'kontak')->value('content'));
    }
}

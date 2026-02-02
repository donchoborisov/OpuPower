<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\PagesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('core')]
class CorePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PagesTableSeeder::class);
    }

    public function test_home_page_loads()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_about_page_loads()
    {
        $response = $this->get('/about-us');

        $response->assertStatus(200);
    }

    public function test_contact_page_loads()
    {
        $response = $this->get('/contact-page');

        $response->assertStatus(200);
    }

    public function test_single_page_loads()
    {
        $page = Page::firstOrFail();

        $response = $this->get('/page/' . $page->id);

        $response->assertStatus(200);
    }
}

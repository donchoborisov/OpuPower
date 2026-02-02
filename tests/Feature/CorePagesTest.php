<?php

namespace Tests\Feature;

use Tests\TestCase;
use TCG\Voyager\Models\Page;

/**
 * @group core
 */
class CorePagesTest extends TestCase
{
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

    public function test_single_page_loads()
    {
        $page = Page::firstOrFail();

        $response = $this->get('/page/' . $page->id);

        $response->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('core')]
class AdminPanelTest extends TestCase
{
    public function test_admin_login_page_loads()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }
}

<?php

namespace Tests\Feature\API\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase; // Memastikan database di-reset setiap kali test berjalan

    public function test_user_dapat_melihat_daftar_task()
    {
        // Arrange - Membuat 2 data task tiruan (Ganti Factory() jadi factory())
        $tasks = Task::factory()->count(2)->create();

        // Act - Melakukan request GET ke API daftar task
        $response = $this->getJson('/api/v1/tasks');

        // Assert - Memastikan status response adalah 200 OK
        $response->assertStatus(200);
    }

    public function test_user_dapat_melihat_satu_task()
    {
        // Arrange - Membuat 1 data task tiruan
        $task = Task::factory()->create();

        // Act - Melakukan request GET ke API satu task spesifik berdasarkan ID-nya
        $response = $this->getJson('/api/v1/tasks/' . $task->id);

        // Assert - Memastikan status response adalah 200 OK
        $response->assertStatus(200);
    }
}

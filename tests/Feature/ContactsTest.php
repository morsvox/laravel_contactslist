<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Faker\Generator as Faker;


use Illuminate\Support\Str;

use App\User;

class ContactsTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    use WithFaker;

    private $contactName;
    private $contactPhone;
    private $user;
    private $contact;

    public function setUp() : void
    {
        parent::setUp();

        $this->contactName = $this->faker->name;
        $this->contactPhone = $this->faker->e164PhoneNumber;

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, "api");
    }

    public function testGet()
    {
        $this->user->contacts()->create([
            "name" => $this->contactName,
            "phone" => $this->contactPhone
        ]);

        $response = $this->json("GET", "/api/contact");

        $response
            ->assertStatus(200)
            ->assertJsonFragment([$this->contactPhone]);
    }

    public function testCreate()
    {
        $response = $this->json("POST", "/api/contact", [
            "name" => $this->contactName,
            "phone" => $this->contactPhone
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonFragment([$this->contactPhone]);
    }

    public function testUpdate()
    {
        $fav = "1";
        
        $contact = $this->user->contacts()->create([
            "name" => $this->contactName,
            "phone" => $this->contactPhone
        ]);

        $response = $this->json("PUT", "/api/contact/$contact->id", [
            "favorite" => $fav
        ]);

        $contactNew = $this->user->contacts()->find($contact->id);

        $response->assertStatus(200);
        
        $this->assertTrue($contactNew->favorite == $fav);
    }

    public function testDelete()
    {
        $contact = $this->user->contacts()->create([
            "name" => $this->contactName,
            "phone" => $this->contactPhone
        ]);

        $response = $this->json("DELETE", "/api/contact/$contact->id");

        $response
            ->assertStatus(200)
            ->assertJsonFragment(["result" => true]);
    }
}

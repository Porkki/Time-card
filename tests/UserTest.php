<?php

use PHPUnit\Framework\TestCase;
require_once "models/user.php";
final class UserTest extends TestCase {
    private $user;
    protected function setUp(): void {
        $this->user = User::withID(22);
        $this->user->username = "AlustusKäyttäjätunnus";
        $this->user->class = "AlustusLuokka";
        $this->user->firstname = "AlustusEtunimi";
        $this->user->lastname = "AlustusSukunimi";
        $this->user->user_company_id = 1000;
        $this->user->setPassword("salasana");
        $this->user->updateInstanceToDB();
    }

    public function testLoadNewUserObjectById() {
        $this->user = User::withID(22);
        $this->assertEquals("AlustusKäyttäjätunnus", $this->user->username);
        $this->assertEquals("AlustusLuokka", $this->user->class);
        $this->assertEquals("AlustusEtunimi", $this->user->firstname);
        $this->assertEquals("AlustusSukunimi", $this->user->lastname);
    }

    public function testLoadNewUserObjectByFalseId() {
        $this->user = User::withID(0);
        $this->assertEquals("Syötetyllä ID:llä ei löydy käyttäjää.", $this->user->error);
    }

    public function testLoadNewUserObjectByIdWithPassword() {
        $this->user = User::withIDLoadPassword(22);
        $this->assertEquals("AlustusKäyttäjätunnus", $this->user->username);
        $this->assertEquals("AlustusLuokka", $this->user->class);
        $this->assertEquals("AlustusEtunimi", $this->user->firstname);
        $this->assertEquals("AlustusSukunimi", $this->user->lastname);
        $this->assertTrue($this->user->checkPassword("salasana"));
    }

    public function testLoadNewUserObjectByFalseIdWithPassword() {
        $this->user = User::withIDLoadPassword(0);
        $this->assertEquals("Syötetyllä ID:llä ei löydy käyttäjää.", $this->user->error);
    }

    public function testLoadNewUserObjectByUsernameAndPassword() {
        $this->user = User::withUsernameAndPassword("Alustuskäyttäjätunnus", "salasana");
        $this->assertEquals("AlustusKäyttäjätunnus", $this->user->username);
        $this->assertEquals("AlustusLuokka", $this->user->class);
        $this->assertEquals("AlustusEtunimi", $this->user->firstname);
        $this->assertEquals("AlustusSukunimi", $this->user->lastname);
    }

    public function testLoadNewUserObjectByFalseUsernameOrPassword() {
        $this->user = User::withUsernameAndPassword("","");
        $this->assertEquals("Tarkista käyttäjätunnus ja/tai salasana.", $this->user->error);
    }

    public function testLoadNewUserObjectByUsername() {
        $this->user = User::withUsername("Alustuskäyttäjätunnus");
        $this->assertEquals("AlustusKäyttäjätunnus", $this->user->username);
        $this->assertEquals("AlustusLuokka", $this->user->class);
        $this->assertEquals("AlustusEtunimi", $this->user->firstname);
        $this->assertEquals("AlustusSukunimi", $this->user->lastname);
    }

    public function testLoadNewUserObjectByFalseUsername() {
        $this->user = User::withUsername("");
        $this->assertEquals("Käyttäjänimeä ei ole olemassa.", $this->user->error);
    }

    public function testUpdateUserObjectToDBAndReadBack() {
        $this->user = User::withID(22);
        $this->user->username = "TestiKäyttäjätunnus";
        $this->user->class = "TestiLuokka";
        $this->user->firstname = "TestiEtunimi";
        $this->user->lastname = "TestiSukunimi";
        $this->user->setPassword("TestiSalasana");
        $this->assertTrue($this->user->updateInstanceToDB());
        $this->user = User::withUsernameAndPassword("TestiKäyttäjätunnus","TestiSalasana");
        $this->assertEquals("TestiKäyttäjätunnus", $this->user->username);
        $this->assertEquals("TestiLuokka", $this->user->class);
        $this->assertEquals("TestiEtunimi", $this->user->firstname);
        $this->assertEquals("TestiSukunimi", $this->user->lastname);
    }

    protected function tearDown(): void {
        unset($this->user);
    }
}
?>
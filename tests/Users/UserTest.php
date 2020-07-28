<?php

namespace ATDev\RocketChat\Tests\Users;

use PHPUnit\Framework\TestCase;
use AspectMock\Test as test;

use ATDev\RocketChat\Users\User;
use ATDev\RocketChat\Users\AvatarFromFile;
use ATDev\RocketChat\Users\AvatarFromDomain;

class UserTest extends TestCase
{
    public function testListingFailed()
    {
        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "send" => true,
            "getSuccess" => false,
            "getResponse" => (object) [],
            "createOutOfResponse" => "nothing"
        ]);

        $result = User::listing();

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.list", "GET"]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("getResponse");
        $stub->verifyNeverInvoked("createOutOfResponse");
    }

    public function testListingSuccess()
    {
        $user1 = new ResponseFixture1();
        $user2 = new ResponseFixture2();
        $response = (object) ["users" => [$user1, $user2]];

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "send" => true,
            "getSuccess" => true,
            "getResponse" => $response,
            "createOutOfResponse" => function ($arg) { return get_class($arg); }
        ]);

        $coll = test::double("\ATDev\RocketChat\Users\Collection", [
            "add" => true
        ]);

        $result = User::listing();

        $this->assertInstanceOf("\ATDev\RocketChat\Users\Collection", $result);
        $stub->verifyInvokedOnce("send", ["users.list", "GET"]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("getResponse");
        $stub->verifyInvokedOnce("createOutOfResponse", [$user1]);
        $stub->verifyInvokedOnce("createOutOfResponse", [$user2]);
        $coll->verifyInvokedOnce("add", ["ATDev\RocketChat\Tests\Users\ResponseFixture1"]);
        $coll->verifyInvokedOnce("add", ["ATDev\RocketChat\Tests\Users\ResponseFixture2"]);
    }

    public function testCreateFailed()
    {
        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "send" => true,
            "getSuccess" => false,
            "getResponse" => (object) [],
            "updateOutOfResponse" => "nothing"
        ]);

        $user = new User();
        $result = $user->create();

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.create", "POST", $user]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("getResponse");
        $stub->verifyNeverInvoked("updateOutOfResponse");
    }

    public function testCreateSuccess()
    {
        $response = (object) ["user" => "user content"];

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "send" => true,
            "getSuccess" => true,
            "getResponse" => $response,
            "updateOutOfResponse" => "result"
        ]);

        $user = new User();
        $result = $user->create();

        $this->assertSame("result", $result);
        $stub->verifyInvokedOnce("send", ["users.create", "POST", $user]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("getResponse");
        $stub->verifyInvokedOnce("updateOutOfResponse", ["user content"]);
    }

    public function testUpdateFailed()
    {
        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => false,
            "getResponse" => (object) [],
            "updateOutOfResponse" => "nothing"
        ]);

        $user = new User();
        $result = $user->update();

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.update", "POST", ["userId" => "userId123", "data" => $user]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("getResponse");
        $stub->verifyNeverInvoked("updateOutOfResponse");
    }

    public function testUpdateSuccess()
    {
        $response = (object) ["user" => "user content"];

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => true,
            "getResponse" => $response,
            "updateOutOfResponse" => "result"
        ]);

        $user = new User();
        $result = $user->update();

        $this->assertSame("result", $result);
        $stub->verifyInvokedOnce("send", ["users.update", "POST", ["userId" => "userId123", "data" => $user]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("getResponse");
        $stub->verifyInvokedOnce("updateOutOfResponse", ["user content"]);
    }

    public function testInfoFailed()
    {
        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => false,
            "getResponse" => (object) [],
            "updateOutOfResponse" => "nothing"
        ]);

        $user = new User();
        $result = $user->info();

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.info", "GET", ["userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("getResponse");
        $stub->verifyNeverInvoked("updateOutOfResponse");
    }

    public function testInfoSuccess()
    {
        $response = (object) ["user" => "user content"];

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => true,
            "getResponse" => $response,
            "updateOutOfResponse" => "result"
        ]);

        $user = new User();
        $result = $user->info();

        $this->assertSame("result", $result);
        $stub->verifyInvokedOnce("send", ["users.info", "GET", ["userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("getResponse");
        $stub->verifyInvokedOnce("updateOutOfResponse", ["user content"]);
    }

    public function testDeleteFailed()
    {
        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => false,
            "setUserId" => "nothing"
        ]);

        $user = new User();
        $result = $user->delete();

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.delete", "POST", ["userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("setUserId");
    }

    public function testDeleteSuccess()
    {
        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => true,
            "setUserId" => "result"
        ]);

        $user = new User();
        $result = $user->delete();

        $this->assertSame("result", $result);
        $stub->verifyInvokedOnce("send", ["users.delete", "POST", ["userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("setUserId", [null]);
    }

    public function testSetAvatarFilepathFailed()
    {
        $user = new User();

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => false,
        ]);

        test::double("\ATDev\RocketChat\Users\AvatarFromFile", [
            "getSource" => "some-path"
        ]);

        $result = $user->setAvatar(new AvatarFromFile());

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.setAvatar", "POST", ["userId" => "userId123"], ["image" => "some-path"]]);
        $stub->verifyInvokedOnce("getSuccess");
    }

    public function testSetAvatarUrlFailed()
    {
        $user = new User();

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => false
        ]);

        test::double("\ATDev\RocketChat\Users\AvatarFromDomain", [
            "getSource" => "some-domain"
        ]);

        $result = $user->setAvatar(new AvatarFromDomain());

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.setAvatar", "POST", ["userId" => "userId123", "avatarUrl" => "some-domain"]]);
        $stub->verifyInvokedOnce("getSuccess");
    }

    public function testSetAvatarFilepathSuccess()
    {
        $user = new User();

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => true
        ]);

        test::double("\ATDev\RocketChat\Users\AvatarFromFile", [
            "getSource" => "some-path"
        ]);

        $result = $user->setAvatar(new AvatarFromFile());

        $this->assertSame($user, $result);
        $stub->verifyInvokedOnce("send", ["users.setAvatar", "POST", ["userId" => "userId123"], ["image" => "some-path"]]);
        $stub->verifyInvokedOnce("getSuccess");
    }

    public function testSetAvatarUrlSuccess()
    {
        $user = new User();

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => true
        ]);

        test::double("\ATDev\RocketChat\Users\AvatarFromDomain", [
            "getSource" => "some-domain"
        ]);

        $result = $user->setAvatar(new AvatarFromDomain());

        $this->assertSame($user, $result);
        $stub->verifyInvokedOnce("send", ["users.setAvatar", "POST", ["userId" => "userId123", "avatarUrl" => "some-domain"]]);
        $stub->verifyInvokedOnce("getSuccess");
    }

    public function testGetAvatarFailed()
    {
        $user = new User();

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => false,
            "getResponseUrl" => null,
            "setAvatarUrl" => true
        ]);

        $result = $user->getAvatar();

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.getAvatar", "GET", ["userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("getResponseUrl");
        $stub->verifyNeverInvoked("setAvatarUrl");
    }

    public function testGetAvatarNoUrlAvailable()
    {
        $user = new User();

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => true,
            "getResponseUrl" => null,
            "setAvatarUrl" => true
        ]);

        $result = $user->getAvatar();

        $this->assertSame($user, $result);
        $stub->verifyInvokedOnce("send", ["users.getAvatar", "GET", ["userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("getResponseUrl");
        $stub->verifyNeverInvoked("setAvatarUrl");
    }

    public function testGetAvatarSuccess()
    {
        $user = new User();

        $stub = test::double("\ATDev\RocketChat\Users\User", [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => true,
            "getResponseUrl" => "some-url",
            "setAvatarUrl" => $user
        ]);

        $result = $user->getAvatar();

        $this->assertSame($user, $result);
        $stub->verifyInvokedOnce("send", ["users.getAvatar", "GET", ["userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedMultipleTimes("getResponseUrl", 2);
        $stub->verifyInvokedOnce("setAvatarUrl", ["some-url"]);
    }

    public function testUpdateOwnBasicInfoFailed()
    {
        $stub = test::double(User::class, [
            "send" => true,
            "getSuccess" => false
        ]);
        $updateData = [
            'email' => 'test@updated.com',
            'name' => 'Updated Name'
        ];

        $result = User::updateOwnBasicInfo($updateData);

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.updateOwnBasicInfo", "POST", ["data" => $updateData]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("getResponse");
        $stub->verifyNeverInvoked("createOutOfResponse");
    }

    public function testUpdateOwnBasicInfoSuccess()
    {
        $updateData = [
            'email' => 'test@updated.com',
            'name' => 'Updated Name'
        ];
        $response = (object) ["user" => (object) $updateData];
        $stub = test::double(User::class, [
            "send" => true,
            "getSuccess" => true,
            "getResponse" => $response,
            "createOutOfResponse" => "updated user result"
        ]);

        $result = User::updateOwnBasicInfo($updateData);

        $this->assertSame("updated user result", $result);
        $stub->verifyInvokedOnce("send", ["users.updateOwnBasicInfo", "POST", ["data" => $updateData]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("getResponse");
        $stub->verifyInvokedOnce("createOutOfResponse");
    }

    public function testSetActiveStatusFailed()
    {
        $stub = test::double(User::class, [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => false
        ]);

        $user = new User();
        $result = $user->setActiveStatus(true);

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.setActiveStatus", "POST", ["activeStatus" => true, "userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("getResponse");
        $stub->verifyNeverInvoked("updateOutOfResponse");
    }

    public function testSetActiveStatusSuccess()
    {
        $response = (object) ["user" => "user content"];
        $stub = test::double(User::class, [
            "getUserId" => "userId123",
            "send" => true,
            "getSuccess" => true,
            "getResponse" => $response,
            "updateOutOfResponse" => "result"
        ]);

        $user = new User();
        $result = $user->setActiveStatus(false);

        $this->assertSame("result", $result);
        $stub->verifyInvokedOnce("send", ["users.setActiveStatus", "POST", ["activeStatus" => false, "userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("getResponse");
        $stub->verifyInvokedOnce("updateOutOfResponse", ["user content"]);
    }

    public function testSetStatusFailed()
    {
        $stub = test::double(User::class, ["send" => true, "getSuccess" => false]);
        $result = User::setStatus("Status message");

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.setStatus", "POST", ["message" => "Status message"]]);
        $stub->verifyInvokedOnce("getSuccess");
    }

    public function testSetStatusSuccess()
    {
        $stub = test::double(User::class, ["send" => true, "getSuccess" => true]);
        $result = User::setStatus("Status message", "away");

        $this->assertSame(true, $result);
        $stub->verifyInvokedOnce("send", ["users.setStatus", "POST", ["message" => "Status message", "status" => "away"]]);
        $stub->verifyInvokedOnce("getSuccess");
    }

    public function testGetStatusFailed()
    {
        $stub = test::double(User::class, ["send" => true, "getSuccess" => false]);
        $result = User::getStatus();

        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.getStatus", "GET", []]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("createOutOfResponse");
        $stub->verifyNeverInvoked("updateOutOfResponse");
    }

    public function testGetStatusWithUsernameFailed()
    {
        $stub = test::double(User::class, [
            "send" => true,
            "getSuccess" => false,
            "getUsername" => "user-name",
            "getUserId" => null,
        ]);

        $user = new User();
        $result = User::getStatus($user);
        $this->assertSame(false, $result);
        $stub->verifyInvokedOnce("send", ["users.getStatus", "GET", ["username" => "user-name"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyNeverInvoked("createOutOfResponse");
        $stub->verifyNeverInvoked("updateOutOfResponse");
    }

    public function testGetStatusSuccess()
    {
        $response = (object) [
            "message" => "Latest status",
            "connectionStatus" => "online",
            "status" => "online"
        ];
        $stub = test::double(User::class, [
            "send" => true,
            "getSuccess" => true,
            "getResponse" => $response,
            "createOutOfResponse" => "userInstance"
        ]);

        $result = User::getStatus();
        $this->assertSame("userInstance", $result);
        $stub->verifyInvokedOnce("send", ["users.getStatus", "GET", []]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("createOutOfResponse", $response);
        $stub->verifyNeverInvoked("updateOutOfResponse");
    }

    public function testGetStatusWithUserIdSuccess()
    {
        $response = (object) [
            "_id" => "userId123",
            "message" => "Latest status",
            "connectionStatus" => "online",
            "status" => "online"
        ];
        $stub = test::double(User::class, [
            "send" => true,
            "getSuccess" => true,
            "getResponse" => $response,
            "updateOutOfResponse" => "userInstance",
            "getUserId" => "userId123"
        ]);

        $user = new User();
        $result = User::getStatus($user);
        $this->assertSame("userInstance", $result);
        $stub->verifyInvokedOnce("send", ["users.getStatus", "GET", ["userId" => "userId123"]]);
        $stub->verifyInvokedOnce("getSuccess");
        $stub->verifyInvokedOnce("updateOutOfResponse", $response);
        $stub->verifyNeverInvoked("createOutOfResponse");
    }

    protected function tearDown(): void
    {
        test::clean(); // remove all registered test doubles
    }
}

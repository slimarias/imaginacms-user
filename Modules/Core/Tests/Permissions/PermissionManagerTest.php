<?php namespace Modules\Core\Tests\Permissions;

use Modules\Core\Tests\BaseTestCase;
use Mockery;
use Modules\Core\Permissions\PermissionManager;

class PermissionManagerTest extends BaseTestCase
{
    /**
     * @var PermissionManager
     */
    protected $permissions;

    public function setUp()
    {
        parent::setUp();

        $enabledModules = [
            'Session',
            'User',
            'Dashboard',
            'Core'
        ];

        $moduleMock = Mockery::mock('Pingpong\Modules\Module');
        $moduleMock->shouldReceive('enabled')->once()->andReturn($enabledModules);

        $this->permissions = new PermissionManager($moduleMock);
    }

    public function itShouldCleanPermissionsCorrectlyTypeCasted()
    {
        $requestData = [
            'dashboard.index' => 'true',
            'users.index' => 'true',
            'users.create' => 'true',
            'users.edit' => 'true',
            'users.delete' => 'true',
            'roles.create' => 'true',
        ];
        $expected = [
            'dashboard.index' => true,
            'users.index' => true,
            'users.create' => true,
            'users.edit' => true,
            'users.delete' => true,
            'roles.create' => true,
        ];
        $cleanedPermissions = $this->permissions->clean($requestData);

        $this->assertEquals($expected, $cleanedPermissions);
    }

    /** @test */
    public function itShouldReturnEmptyArrayIfNoRolesAreSupplied()
    {
        $request = [];

        $expected = [];

        $cleanedPermissions = $this->permissions->clean($request);

        $this->assertEquals($expected, $cleanedPermissions);
    }
}

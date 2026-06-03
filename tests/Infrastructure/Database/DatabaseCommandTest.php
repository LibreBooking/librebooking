<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');

class DatabaseCommandTest extends TestBase
{
    public function testAuthorizationCommand()
    {
        $username = 'loGin';

        $command = new AuthorizationCommand($username);

        $this->assertEquals(Queries::VALIDATE_USER, $command->GetQuery());
        $this->assertEquals(1, $command->Parameters->Count());

        $par1 = $command->Parameters->Items(0);

        $this->assertEquals(ParameterNames::USERNAME, $par1->Name);
        $this->assertEquals(strtolower($username), $par1->Value);
    }

    public function testLoginCommand()
    {
        $username = 'loGin';

        $command = new LoginCommand($username);

        $this->assertEquals(Queries::LOGIN_USER, $command->GetQuery());
        $this->assertEquals(1, $command->Parameters->Count());

        $par1 = $command->Parameters->Items(0);

        $this->assertEquals(ParameterNames::USERNAME, $par1->Name);
        $this->assertEquals(strtolower($username), $par1->Value);
    }

    public function testMigratePasswordCommand()
    {
        $userid = 1;
        $password = 'encrypted';
        $salt = 'salt';

        $command = new MigratePasswordCommand($userid, $password, $salt);

        $this->assertEquals(Queries::MIGRATE_PASSWORD, $command->GetQuery());
        $this->assertEquals(3, $command->Parameters->Count());

        $par1 = $command->Parameters->Items(0);
        $par2 = $command->Parameters->Items(1);
        $par3 = $command->Parameters->Items(2);

        $this->assertEquals(ParameterNames::USER_ID, $par1->Name);
        $this->assertEquals($userid, $par1->Value);

        $this->assertEquals(ParameterNames::PASSWORD, $par2->Name);
        $this->assertEquals($password, $par2->Value);

        $this->assertEquals(ParameterNames::SALT, $par3->Name);
        $this->assertEquals($salt, $par3->Value);
    }

    public function testCookieLoginCommand()
    {
        $userid = 10;
        $command = new CookieLoginCommand($userid);

        $this->assertEquals(Queries::COOKIE_LOGIN, $command->GetQuery());
        $this->assertEquals(1, $command->Parameters->Count());

        $par1 = $command->Parameters->Items(0);
        $this->assertEquals(new Parameter(ParameterNames::USER_ID, $userid), $par1);
    }

    public function testCheckUserExistenceCommand()
    {
        $username = 'username';
        $email = 'email';

        $command = new CheckUserExistenceCommand($username, $email);

        $this->assertEquals(Queries::CHECK_USER_EXISTENCE, $command->GetQuery());
        $this->assertEquals(2, $command->Parameters->Count());

        $this->assertEquals(new Parameter(ParameterNames::USERNAME, $username), $command->Parameters->Items(0));
        $this->assertEquals(new Parameter(ParameterNames::EMAIL_ADDRESS, $email), $command->Parameters->Items(1));
    }

    public function testCheckEmailCommand()
    {
        $email = 'some@email.com';
        $command = new CheckEmailCommand($email);

        $this->assertEquals(Queries::CHECK_EMAIL, $command->GetQuery());
        $this->assertEquals(1, $command->Parameters->Count());
        $this->assertEquals(new Parameter(ParameterNames::EMAIL_ADDRESS, $email), $command->Parameters->Items(0));
    }

    public function testCheckUsernameCommand()
    {
        $username = 'username';
        $command = new CheckUsernameCommand($username);

        $this->assertEquals(Queries::CHECK_USERNAME, $command->GetQuery());
        $this->assertEquals(1, $command->Parameters->Count());
        $this->assertEquals(new Parameter(ParameterNames::USERNAME, $username), $command->Parameters->Items(0));
    }

    public function testGetUserRoleCommand()
    {
        $userid = 123;

        $command = new GetUserRoleCommand($userid);
        $this->assertEquals(Queries::GET_USER_ROLES, $command->GetQuery());
        $this->assertEquals(1, $command->Parameters->Count());
        $this->assertEquals(new Parameter(ParameterNames::USER_ID, $userid), $command->Parameters->Items(0));
    }

    public function testUpdateUserFromLdapCommand()
    {
        $username = 'testlogin';
        $email = 'test@test.com';
        $fname = 'First';
        $lname = 'Last';
        $password = 'password';
        $salt = '23948';
        $phone = '123.123.1234';
        $institution = 'inst';
        $position = 'pos';

        $command = new UpdateUserFromLdapCommand($username, $email, $fname, $lname, $password, $salt, $phone, $institution, $position);

        $this->assertEquals(Queries::UPDATE_USER_BY_USERNAME, $command->GetQuery());
        $this->assertEquals(9, $command->Parameters->Count());
        $this->assertEquals(new Parameter(ParameterNames::USERNAME, $username), $command->Parameters->Items(0));
        $this->assertEquals(new Parameter(ParameterNames::EMAIL_ADDRESS, $email), $command->Parameters->Items(1));
        $this->assertEquals(new Parameter(ParameterNames::FIRST_NAME, $fname), $command->Parameters->Items(2));
        $this->assertEquals(new Parameter(ParameterNames::LAST_NAME, $lname), $command->Parameters->Items(3));
        $this->assertEquals(new Parameter(ParameterNames::PASSWORD, $password), $command->Parameters->Items(4));
        $this->assertEquals(new Parameter(ParameterNames::SALT, $salt), $command->Parameters->Items(5));
        $this->assertEquals(new Parameter(ParameterNames::PHONE, $phone), $command->Parameters->Items(6));
        $this->assertEquals(new Parameter(ParameterNames::ORGANIZATION, $institution), $command->Parameters->Items(7));
        $this->assertEquals(new Parameter(ParameterNames::POSITION, $position), $command->Parameters->Items(8));
    }

    public function testAutoAssignPermissionsCommand()
    {
        $id = 101;

        $command = new AutoAssignPermissionsCommand($id);
        $this->assertEquals(Queries::AUTO_ASSIGN_PERMISSIONS, $command->GetQuery());
        $this->assertEquals(1, $command->Parameters->Count());
        $this->assertEquals(new Parameter(ParameterNames::USER_ID, $id), $command->Parameters->Items(0));
    }

    public function testGetDashboardAnnouncementsCommand()
    {
        $now = new Date();
        $displayPage = 1;

        $command = new GetDashboardAnnouncementsCommand($now, $displayPage);
        $this->assertEquals(Queries::GET_DASHBOARD_ANNOUNCEMENTS, $command->GetQuery());
        $this->assertEquals(2, $command->Parameters->Count());
        $this->assertEquals(new Parameter(ParameterNames::CURRENT_DATE, $now->ToDatabase()), $command->Parameters->Items(0));
    }

    public function testGetAllSchedulesCommand()
    {
        $command = new GetAllSchedulesCommand();
        $this->assertEquals(Queries::GET_ALL_SCHEDULES, $command->GetQuery());
        $this->assertEquals(0, $command->Parameters->Count());
    }

    public function testGetScheduleResourcesCommand()
    {
        $scheduleId = 10;

        $command = new GetScheduleResourcesCommand($scheduleId);

        $this->assertEquals(Queries::GET_SCHEDULE_RESOURCES, $command->GetQuery());
        $this->assertEquals(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId), $command->Parameters->Items(0));
    }

    public function testSelectUserGroupPermissionsCommand()
    {
        $userId = 1;

        $command = new SelectUserGroupPermissions($userId);

        $this->assertEquals(Queries::GET_USER_GROUP_RESOURCE_PERMISSIONS, $command->GetQuery());
        $this->assertEquals(new Parameter(ParameterNames::USER_ID, $userId), $command->Parameters->Items(0));
    }

    public function testGetLayoutCommand()
    {
        $scheduleId = 1;

        $command = new GetLayoutCommand($scheduleId);

        $this->assertEquals(Queries::GET_SCHEDULE_TIME_BLOCK_GROUPS, $command->GetQuery());
        $this->assertEquals(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId), $command->Parameters->Items(0));
    }

    public function testGetAllUsersByStatusCommand()
    {
        $statusId = AccountStatus::ACTIVE;
        $command = new GetAllUsersByStatusCommand($statusId);

        $this->assertEquals(Queries::GET_ALL_USERS_BY_STATUS, $command->GetQuery());
        $this->assertEquals(new Parameter(ParameterNames::USER_STATUS_ID, $statusId), $command->Parameters->Items(0));
    }

    public function testCountCommandReplacesSelectBlahFromWithSelectCountFrom()
    {
        $baseSql = 'SeLEcT F.*,    lbl,
													abc.* fROM table WHERE blah = blah';
        $baseCommand = new AdHocCommand($baseSql);
        $countCommand = new CountCommand($baseCommand);

        $this->assertEquals("SELECT COUNT(*) as `total` FROM ($baseSql) `results`", $countCommand->GetQuery());
    }

    public function testFilterCommandWrapsAppendsToWhere()
    {
        $baseCommand = new AdHocCommand('SeLEcT F.*,    lbl,
											abc.* fROM table wHere blah = @blah and blah2 = @blah2 GROUP BY 1, 2 ORDER BY blah1');

        $filter = new SqlFilterLike('fname', 'firstname');
        $filter->_And(new SqlFilterEquals('lname', 'last'));

        $filterCommand = new FilterCommand($baseCommand, $filter);

        $this->assertEquals(2, $filterCommand->Parameters->Count());
        $this->assertEquals('%firstname%', $filterCommand->Parameters->Items(0)->Value);
        $this->assertEquals('last', $filterCommand->Parameters->Items(1)->Value);

        $constraint = $this->stringContains('table WHERE ( blah = @blah and blah2 = @blah2 ) AND (`fname` LIKE @fname AND ( `lname` = @lname )) GROUP BY 1, 2 ORDER BY blah1');
        $query = $filterCommand->GetQuery();
        $this->assertThat($query, $constraint, $query);
    }

    public function testFiltersWithoutOrderBy()
    {
        $baseCommand = new AdHocCommand("SELECT *
						FROM users
						WHERE (0 = '0' OR status_id = '0')");

        $filter = new SqlFilterLike('fname', 'firstname');
        $filter->_And(new SqlFilterEquals('lname', 'last'));

        $filterCommand = new FilterCommand($baseCommand, $filter);

        $constraint = $this->stringContains("WHERE ( (0 = '0' OR status_id = '0')) AND (`fname` LIKE @fname AND ( `lname` = @lname ))");
        $query = $filterCommand->GetQuery();
        $this->assertThat($query, $constraint, $query);
    }

    public function testFiltersWithoutWhere()
    {
        $baseCommand = new AdHocCommand('SELECT *
						FROM users
						GROUP BY 1, 2 ORDER BY 3, 4');

        $filter = new SqlFilterLike('fname', 'firstname');
        $filter->_And(new SqlFilterEquals('lname', 'last'));

        $filterCommand = new FilterCommand($baseCommand, $filter);

        $constraint = $this->stringContains('WHERE `fname` LIKE @fname AND ( `lname` = @lname ) GROUP BY 1, 2 ORDER BY 3, 4');
        $query = $filterCommand->GetQuery();
        $this->assertThat($query, $constraint, $query);
    }

    public function testFiltersWithInClause()
    {
        $baseCommand = new AdHocCommand("SELECT * FROM users WHERE (0 = '0' OR status_id = '0')");

        $filter = new SqlFilterIn('fname', ["n'k", '123']);

        $filterCommand = new FilterCommand($baseCommand, $filter);

        $constraint = $this->stringContains("WHERE ( (0 = '0' OR status_id = '0')) AND (`fname` IN ('n''k','123'))");
        $query = $filterCommand->GetQuery();
        $this->assertThat($query, $constraint, $query);
    }

    public function testGetGroupReservations()
    {
        $command = new GetFullGroupReservationListCommand([1,2]);
        $filterCommand = new FilterCommand($command, new SqlFilterEquals(ColumnNames::ACCESSORY_NAME, 'something just to make sure filter does not break subquery'));
        $countCommand = new CountCommand($filterCommand);

        $containsSubQuery = $this->stringContains('INNER JOIN (SELECT user_id FROM user_groups WHERE group_id IN (@groupid)) ss on ss.user_id = owner_id WHERE ', false);
        $containsFilter = $this->stringContains('AND (`accessory_name` = @accessory_name)', false);

        $query = $filterCommand->GetQuery();
        $countQuery = $countCommand->GetQuery();
        $this->assertThat($query, $containsSubQuery, $query);
        $this->assertThat($query, $containsFilter, $query);
        $this->assertThat($countQuery, $containsSubQuery, $countQuery);
        $this->assertThat($countQuery, $containsFilter, $countQuery);
    }

    public function testSorts()
    {
        $command = new SortCommand(new AdHocCommand('SELECT u.*,
			ORDER BY something that shouldnt change
			FROM users u
			WHERE (whatever = whatever else) ORDER BY lname, fname'), 'email', 'desc');

        $expected = 'SELECT u.*,
			ORDER BY something that shouldnt change
			FROM users u
			WHERE (whatever = whatever else) ORDER BY @sort_params desc';

        $this->assertEquals($expected, $command->GetQuery());
        $this->assertEquals(new ParameterRaw('@sort_params', 'email'), $command->Parameters->Items(0));
    }
}

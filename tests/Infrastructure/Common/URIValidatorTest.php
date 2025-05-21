<?php

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class URIValidatorTest  extends TestBase
{
    public function testValidPathIsAccepted()
    {
        $uri = '/Web/view-schedule.php';
        $this->assertTrue(URIScriptValidator::validate($uri));
    }

    public function testInvalidScriptInQueryTriggersRedirect()
    {
        $uri = '/Web/view-schedule.php?<script>alert(1)</script>';
        $this->assertFalse(URIScriptValidator::validate($uri));
    }
}

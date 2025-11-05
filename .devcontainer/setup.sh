# Setup LDAP
mkdir -p plugins/Authentication/Ldap plugins/Authentication/ActiveDirectory
echo "<?php return ['enabled' => false];" > plugins/Authentication/Ldap/Ldap.config.php
echo "<?php return ['enabled' => false];" > plugins/Authentication/ActiveDirectory/ActiveDirectory.config.php
# Copy default config to be usable right away for testing
cp config/config.dist.php config/config.php
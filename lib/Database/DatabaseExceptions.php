<?php

class DatabaseException extends Exception
{
}

class DatabaseConnectionException extends DatabaseException
{
}

class DatabaseNotFoundException extends DatabaseException
{
}

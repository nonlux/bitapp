@f0002
Feature:bitapp bitrix:dump:standard
  As user
  I can dump standard bitrix pack in project folder.

  @f0003.s0002
  Scenario: I should dump files from standard bitrix pack
    When I execute "bitrix:dump:standard"
    Then File "index.php" in test project should have "bdd19afb96918a0c561046022b385780" md5
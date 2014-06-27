@f0001
Feature:bitapp bitrix:clear:all
  As user
  I can clear all data from project

  @f0001.s0001
  Scenario: Prepare tests
    Given  Folder "project" exist

  @f0001.s0002
  Scenario: I should clear file "test.php" from test project
    Given File "test.php" exist in test project
    When I execute "bitrix:clear:all"
    Then File "test.php" shound not exist in test project
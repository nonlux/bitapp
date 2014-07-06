@f0003
Feature:bitapp bitrix:dump:fixture
  As user
  I can dump some fixture to project folder.

  @f0003.s0002
  Scenario: I should dump files from standard bitrix pack
    When I execute "bitrix:dump:fixture" with "test"
    Then File "fixture.php" in test project should have "d5e5671ad01217afe8d37a4dae837b2e" md5
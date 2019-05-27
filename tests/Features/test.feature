Feature:
  Test

  @success
  Scenario: happy_scenario
    Given I send a GET request to "/sudoku"
    Then response status code should be 200

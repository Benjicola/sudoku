@database @delete
Feature:
  Delete a offer from a JSON

  Background:
    Given I use fixture files:
        | eav.yaml                  |
    And I use fixture files:
        | workspace.yaml            |
        | offer_type.yaml           |
        | offer.yaml                |

  @success
  Scenario: happy_scenario
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a DELETE request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json"
    Then response status code should be 204
    And I should not have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"

  @error_scenario.does_not_exist
  Scenario: error_scenario
    Given I should not have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a738"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a DELETE request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a738.json"
    Then response status code should be 404
    And the JSON node "message" should be equal to "Not Found"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "id"
    And the JSON node "errors[0].message" should be equal to "Offer with identifier `a696ee71-d54f-4217-9592-c74eeae5a738` does not exist."

  @error_scenario.no_workspace_in_header
  Scenario: error_scenario - missing header data
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    When I send a DELETE request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Workspace id is missing"

  @error_scenario.header_workspace_not_exist
  Scenario: error_scenario - workspace id does not exist
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a991"
    When I send a DELETE request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "workspaceId"
    And the JSON node "errors[0].message" should be equal to "Workspace with identifier `a696ee71-d54f-4217-9592-c74eeae5a991` does not exist."

  @error_scenario.not_belonging_to_workspace
  Scenario: happy_scenario
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a998"
    When I send a DELETE request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to ""
    And the JSON node "errors[0].message" should be equal to "Resource `a696ee71-d54f-4217-9592-c74eeae5a739` is not belonging to workspace `a696ee71-d54f-4217-9592-c74eeae5a998`."

  @error_scenario.header_workspace_bad_format
  Scenario: error_scenario
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    And I set "X-WORKSPACE-ID" header equal to "uuid"
    When I send a DELETE request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 2 element
    And the JSON node "errors[0].path" should be equal to "workspaceId"
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."
    And the JSON node "errors[1].path" should be equal to "workspaceId"
    And the JSON node "errors[1].message" should be equal to "Workspace with identifier `uuid` does not exist."


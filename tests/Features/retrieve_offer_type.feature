@database @retrieve
Feature:
  Retrieve an offer type from a JSON

  Background:
    Given I use fixture files:
        | eav.yaml                  |
    And I use fixture files:
        | workspace.yaml            |
        | offer_type.yaml           |
        | offer.yaml                |

  @offer_type.success
  Scenario: happy_scenario
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a GET request to "/offer-type/a696ee71-d54f-4217-9592-c74eeae5a895.json"
    Then response status code should be 200
    And the JSON node "id" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a895"
    And the JSON node "name" should be equal to "Test 1"

  @offer_type.does_not_exist
  Scenario: error_scenario
    Given I should not have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a738"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a GET request to "/offer-type/a696ee71-d54f-4217-9592-c74eeae5a738.json"
    Then response status code should be 404
    And the JSON node "message" should be equal to "Not Found"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "id"
    And the JSON node "errors[0].message" should be equal to "OfferType with identifier `a696ee71-d54f-4217-9592-c74eeae5a738` does not exist."

  @offer_type.uuid_bad_format
  Scenario: error_scenario, not an uuid
    Given I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a GET request to "/offer-type/COUCOU.json"
    Then response status code should be 404
    And the JSON node "message" should be equal to "Not Found"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 2 element
    And the JSON node "errors[0].path" should be equal to "id"
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."
    And the JSON node "errors[1].path" should be equal to "id"
    And the JSON node "errors[1].message" should be equal to "OfferType with identifier `COUCOU` does not exist."

  @offer_type.no_workspace_in_header
  Scenario: error_scenario - missing header data
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    When I send a GET request to "/offer-type/a696ee71-d54f-4217-9592-c74eeae5a895.json"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Workspace id is missing"

  @offer_type.header_workspace_not_exist
  Scenario: error_scenario - workspace id does not exist
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a894"
    When I send a GET request to "/offer-type/a696ee71-d54f-4217-9592-c74eeae5a895.json"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "workspaceId"
    And the JSON node "errors[0].message" should be equal to "Workspace with identifier `a696ee71-d54f-4217-9592-c74eeae5a894` does not exist."

  @offer_type.not_belonging_to_workspace
  Scenario: error_scenario
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a998"
    When I send a GET request to "/offer-type/a696ee71-d54f-4217-9592-c74eeae5a895.json"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to ""
    And the JSON node "errors[0].message" should be equal to "Resource `a696ee71-d54f-4217-9592-c74eeae5a895` is not belonging to workspace `a696ee71-d54f-4217-9592-c74eeae5a998`."

  @offer_type.header_workspace_bad_format
  Scenario: error_scenario
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    And I set "X-WORKSPACE-ID" header equal to "uuid"
    When I send a GET request to "/offer-type/a696ee71-d54f-4217-9592-c74eeae5a895.json"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 2 element
    And the JSON node "errors[0].path" should be equal to "workspaceId"
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."
    And the JSON node "errors[1].path" should be equal to "workspaceId"
    And the JSON node "errors[1].message" should be equal to "Workspace with identifier `uuid` does not exist."

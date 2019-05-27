@database @search
Feature:
  Search an offer type

  Background:
    Given I use fixture files:
      | eav.yaml                  |
    And I use fixture files:
      | workspace.yaml            |
      | offer_type.yaml           |
      | offer.yaml                |

  @search.success
  Scenario: happy_scenario
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    When I send a GET request to "/offer-types?name=Test+1&workspace_id=a696ee71-d54f-4217-9592-c74eeae5a997"
    Then response status code should be 200
    And the JSON node "id" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a895"
    And the JSON node "name" should be equal to "Test 1"

  @search.success
  Scenario: happy_scenario name not found
    When I send a GET request to "/offer-types?name=type99&workspace_id=a696ee71-d54f-4217-9592-c74eeae5a997"
    Then response status code should be 200

  @search.success
  Scenario: happy_scenario offer type for workspace_id not found
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    When I send a GET request to "/offer-types?name=Test+1&workspace_id=995a5389-f070-4fda-81e0-82f1838cb42b"
    Then response status code should be 200

  @retrieve.error
  Scenario: error_scenario name missing
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    When I send a GET request to "/offer-types?workspace_id=a696ee71-d54f-4217-9592-c74eeae5a997"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[name]"
    And the JSON node "errors[0].message" should be equal to "This field is missing."

  @retrieve.error
  Scenario: error_scenario name blank
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    When I send a GET request to "/offer-types?name=&workspace_id=61827c8b-58ea-49f9-9b66-2eb95c7bc092"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[name]"
    And the JSON node "errors[0].message" should be equal to "This value should not be blank."

  @retrieve.error
  Scenario: error_scenario workspace blank
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    When I send a GET request to "/offer-types?name=Test+1&workspace_id="
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[workspace_id]"
    And the JSON node "errors[0].message" should be equal to "This value should not be blank."

  @retrieve.error
  Scenario: error_scenario workspace_id missing
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    When I send a GET request to "/offer-types?name=Test+1"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[workspace_id]"
    And the JSON node "errors[0].message" should be equal to "This field is missing."

  @retrieve.error
  Scenario: error_scenario workspace_id not uuid
    Given I should have a "offer_type" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a895"
    When I send a GET request to "/offer-types?name=Test+1&workspace_id=123"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[workspace_id]"
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."

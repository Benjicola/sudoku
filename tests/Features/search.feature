@database @search
Feature:
  Search an offer

  Background:
    Given I use fixture files:
      | eav.yaml                  |
    And I use fixture files:
      | workspace.yaml            |
      | offer_type.yaml           |
      | offer.yaml                |

  @search.success
  Scenario: happy_scenario
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    When I send a GET request to "/offers?offer_id=a696ee71-d54f-4217-9592-c74eeae5a739&workspace_id=a696ee71-d54f-4217-9592-c74eeae5a997"
    Then response status code should be 200
    And the JSON node "id" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a739"
    And the JSON node "offer_type_id" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a895"
    And the JSON node "workspace_ids" should have 1 elements
    And the JSON node "workspace_ids[0]" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    And the JSON node "offer_type_values.myArrayOfIntegerValue[0]" should be equal to "1"
    And the JSON node "offer_type_values.myArrayOfStructureValue[0].foo" should be equal to "bar"
    And the JSON node "offer_type_values.myEntityReference" should be equal to "TODO"
    And the JSON node "offer_type_values.myFloatDprop" should be equal to "13.37"
    And the JSON node "offer_type_values.myIntegerDprop" should be equal to "1337"
    And the JSON node "offer_type_values.myStringDprop" should be equal to "chuck"
    And the JSON node "offer_type_values.myStructureDprop.foo" should be equal to "bar"

  @search.success
  Scenario: happy_scenario offer id not found
    Given I should not have a "offer" entity with identifier "99f881ef-2333-4e19-b617-2e302c49cc97"
    When I send a GET request to "/offers?offer_id=99f881ef-2333-4e19-b617-2e302c49cc97&workspace_id=a696ee71-d54f-4217-9592-c74eeae5a997"
    Then response status code should be 200

  @search.success
  Scenario: happy_scenario offer for workspace_id not found
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    When I send a GET request to "/offers?offer_id=a696ee71-d54f-4217-9592-c74eeae5a739&workspace_id=995a5389-f070-4fda-81e0-82f1838cb42b"
    Then response status code should be 200

  @retrieve.error
  Scenario: error_scenario offer_id missing
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    When I send a GET request to "/offers?workspace_id=a696ee71-d54f-4217-9592-c74eeae5a997"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[offer_id]"
    And the JSON node "errors[0].message" should be equal to "This field is missing."

  @retrieve.error
  Scenario: error_scenario offer_id not uuid
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    When I send a GET request to "/offers?offer_id=123&workspace_id=a696ee71-d54f-4217-9592-c74eeae5a997"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[offer_id]"
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."

  @retrieve.error
  Scenario: error_scenario offer_id blank
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    When I send a GET request to "/offers?offer_id=&workspace_id=a696ee71-d54f-4217-9592-c74eeae5a997"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[offer_id]"
    And the JSON node "errors[0].message" should be equal to "This value should not be blank."

  @retrieve.error
  Scenario: error_scenario workspace_id blank
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    When I send a GET request to "/offers?offer_id=a696ee71-d54f-4217-9592-c74eeae5a739&workspace_id="
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[workspace_id]"
    And the JSON node "errors[0].message" should be equal to "This value should not be blank."

  @retrieve.error
  Scenario: error_scenario workspace_id missing
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    When I send a GET request to "/offers?offer_id=a696ee71-d54f-4217-9592-c74eeae5a739"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[workspace_id]"
    And the JSON node "errors[0].message" should be equal to "This field is missing."

  @retrieve.error
  Scenario: error_scenario workspace_id not uuid
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    When I send a GET request to "/offers?offer_id=a696ee71-d54f-4217-9592-c74eeae5a739&workspace_id=123"
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[workspace_id]"
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."

@database @update
Feature:
  Update a offer from a JSON

  Background:
    Given I use fixture files:
      | eav.yaml                  |
    And I use fixture files:
      | workspace.yaml            |
      | offer_type.yaml           |
      | offer.yaml                |

  @success
  Scenario: happy_scenario
    Given I set "content-type" header equal to "application/json"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a PUT request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json" with body:
    """
    {
        "offer_type_values": {"myArrayOfIntegerValue": [
                4
            ],
            "myArrayOfStructureValue": [
                {
                    "foo": "foo"
                }
            ],
            "myEntityReference": "UPDATE",
            "myFloatDprop": 19.7,
            "myIntegerDprop": 8989,
            "myStringDprop": "norris",
            "myStructureDprop": {
                "foo": "smith"
            }
        }
    }
    """
    Then response status code should be 204
    When I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    And I send a GET request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json"
    Then response status code should be 200
    And the JSON node "id" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a739"
    And the JSON node "offer_type_id" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a895"
    And the JSON node "workspace_ids" should have 1 elements
    And the JSON node "workspace_ids[0]" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    And the JSON node "offer_type_values.myArrayOfIntegerValue[0]" should be equal to "4"
    And the JSON node "offer_type_values.myArrayOfStructureValue[0].foo" should be equal to "foo"
    And the JSON node "offer_type_values.myEntityReference" should be equal to "UPDATE"
    And the JSON node "offer_type_values.myFloatDprop" should be equal to "19.7"
    And the JSON node "offer_type_values.myIntegerDprop" should be equal to "8989"
    And the JSON node "offer_type_values.myStringDprop" should be equal to "norris"
    And the JSON node "offer_type_values.myStructureDprop.foo" should be equal to "smith"

  @error_scenario.bad_request
  Scenario: error_scenario, bad request
    Given I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a PUT request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json" with body:
    """
    not a json
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist

  @error_scenario.invalid_json
  Scenario: error_scenario, bad request
    Given I set "content-type" header equal to "application/json"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a PUT request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json" with body:
    """
    invalid json
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "invalid json body: Syntax error"

  @error_scenario.extra_field
  Scenario: error_scenario, extra fields foo
    Given I set "content-type" header equal to "application/json"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a PUT request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json" with body:
    """
    {
        "foo": "bar",
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[foo]"
    And the JSON node "errors[0].message" should be equal to "This field was not expected."

  @error_scenario.not_found
  Scenario: error_scenario, update an unknown Offer
    Given I set "content-type" header equal to "application/json"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a PUT request to "/offer/b7462cb0-c168-4a12-aee6-8883ebc85a82.json" with body:
    """
    {
        "offer_type_values": {}
    }
    """
    Then response status code should be 404
    And the JSON node "message" should be equal to "Not Found"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "id"
    And the JSON node "errors[0].message" should be equal to "Offer with identifier `b7462cb0-c168-4a12-aee6-8883ebc85a82` does not exist."

  @error_scenario.no_workspace_in_header
  Scenario: error_scenario - missing header data
    Given I set "content-type" header equal to "application/json"
    When I send a PUT request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json" with body:
    """
    {
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Workspace id is missing"

  @error_scenario.header_workspace_not_exist
  Scenario: error_scenario - workspace id does not exist
    Given I set "content-type" header equal to "application/json"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a991"
    When I send a PUT request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json" with body:
    """
    {
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "workspaceId"
    And the JSON node "errors[0].message" should be equal to "Workspace with identifier `a696ee71-d54f-4217-9592-c74eeae5a991` does not exist."

  @error_scenario.not_belonging_to_workspace
  Scenario: error_scenario - entity is not associated with the workspace
    Given I set "content-type" header equal to "application/json"
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a998"
    When I send a PUT request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json" with body:
    """
    {
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to ""
    And the JSON node "errors[0].message" should be equal to "Resource `a696ee71-d54f-4217-9592-c74eeae5a739` is not belonging to workspace `a696ee71-d54f-4217-9592-c74eeae5a998`."

  @error_scenario.header_workspace_bad_format
  Scenario: error_scenario
    Given I set "content-type" header equal to "application/json"
    And I set "X-WORKSPACE-ID" header equal to "uuid"
    When I send a PUT request to "/offer/a696ee71-d54f-4217-9592-c74eeae5a739.json" with body:
    """
    {
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 2 element
    And the JSON node "errors[0].path" should be equal to "workspaceId"
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."
    And the JSON node "errors[1].path" should be equal to "workspaceId"
    And the JSON node "errors[1].message" should be equal to "Workspace with identifier `uuid` does not exist."

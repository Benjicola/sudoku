@database @create
Feature:
  Create a offer from a JSON

  Background:
    Given I use fixture files:
        | eav.yaml                  |
    And I use fixture files:
        | workspace.yaml            |
        | offer_type.yaml           |
        | offer.yaml                |

  @error_scenario.bad_request
  Scenario: error_scenario, bad request
    When I send a POST request to "/offer.json" with body:
    """
    not a json
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist

  @error_scenario.bad_request
  Scenario: error_scenario, bad request
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offer.json" with body:
    """
    invalid json
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "invalid json body: Syntax error"

  @error_scenario.extra_field
  Scenario: error_scenario, extra fields ...
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offer.json" with body:
    """
    {
        "foo": "bar",
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a997"],
        "offer_type_id": "a696ee71-d54f-4217-9592-c74eeae5a895",
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[foo]"
    And the JSON node "errors[0].message" should be equal to "This field was not expected."

  @error_scenario.unwanted_workspace
  Scenario: error_scenario, unwanted workspace
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offer.json" with body:
    """
    {
        "offer_type_id": "a696ee71-d54f-4217-9592-c74eeae5a895",
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a997", "a696ee71-d54f-4217-9592-c74eeae5a998"],
        "offer_type_values": {"color":"red"}
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].message" should be equal to "Current Offer must include all workspaces from OfferType `a696ee71-d54f-4217-9592-c74eeae5a895`."

  @success
  Scenario: happy_scenario
    Given I should not have a "offer" entity with identifier "b7462cb0-c168-4a12-aee6-8883ebc85a81"
    And I set "content-type" header equal to "application/json"
    When I send a POST request to "/offer.json" with body:
    """
    {
        "offer_type_id": "a696ee71-d54f-4217-9592-c74eeae5a895",
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a997"],
        "offer_type_values": {
            "myArrayOfIntegerValue": [
                1
            ],
            "myArrayOfStructureValue": [
                {
                    "foo": "bar"
                }
            ],
            "myEntityReference": "TODO",
            "myFloatDprop": 13.37,
            "myIntegerDprop": 1337,
            "myStringDprop": "norris",
            "myStructureDprop": {
                "foo": "bar"
            }
        }
    }
    """
    Then response status code should be 201
    And the response header "X-RESOURCE-ID" should exist

    Then I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    And I send a GET request to "/offer/%s.json" with "X-RESOURCE-ID" header value retrieved from resource just created before
    Then response status code should be 200
    And the JSON node "id" should exist
    And the JSON node "offer_type_id" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a895"
    And the JSON node "workspace_ids" should have 1 elements
    And the JSON node "workspace_ids[0]" should be equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    And the JSON node "offer_type_values.myArrayOfIntegerValue[0]" should be equal to "1"
    And the JSON node "offer_type_values.myArrayOfStructureValue[0].foo" should be equal to "bar"
    And the JSON node "offer_type_values.myEntityReference" should be equal to "TODO"
    And the JSON node "offer_type_values.myFloatDprop" should be equal to "13.37"
    And the JSON node "offer_type_values.myIntegerDprop" should be equal to "1337"
    And the JSON node "offer_type_values.myStringDprop" should be equal to "norris"
    And the JSON node "offer_type_values.myStructureDprop.foo" should be equal to "bar"

  @error_scenario.workspace_not_exist
  Scenario: error_scenario, workspace does not exist
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offer.json" with body:
     """
    {
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a105"],
        "offer_type_id": "a696ee71-d54f-4217-9592-c74eeae5a895",
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[workspace_ids][0]"
    And the JSON node "errors[0].message" should be equal to "Workspace with identifier `a696ee71-d54f-4217-9592-c74eeae5a105` does not exist."

  @error_scenario.workspace_id.uuid_not_valid
  Scenario: error_scenario, the UUID of ws_id is not valid
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offer.json" with body:
     """
    {
        "workspace_ids": ["a696ee71-d54f-4217-9592"],
        "offer_type_id": "a696ee71-d54f-4217-9592-c74eeae5a895",
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."

  @error_scenario.offer_type.does_not_exist
  Scenario: error_scenario, OfferType does not exist
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offer.json" with body:
     """
    {
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a997"],
        "offer_type_id": "a696ee71-d54f-4217-9592-c74eeae5a700",
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[offer_type_id]"
    And the JSON node "errors[0].message" should be equal to "OfferType with identifier `a696ee71-d54f-4217-9592-c74eeae5a700` does not exist."

  @error_scenario.offer_type_id.uuid_not_valid
  Scenario: error_scenario, the UUID of offer_type_id is not valid
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offer.json" with body:
     """
    {
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a997"],
        "offer_type_id": "a696ee71-d54f-4217-9592-c74eea",
        "offer_type_values": {}
    }
    """
    Then response status code should be 400
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."

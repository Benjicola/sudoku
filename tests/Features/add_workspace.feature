@database @add_workspace
Feature:
  Add workspaces to a project

  Background:
    Given I use fixture files:
      | eav.yaml                  |
    And I use fixture files:
      | workspace.yaml            |
      | offer_type.yaml           |
      | offer.yaml                |

  @add_workspace.error_scenario
  Scenario: error_scenario, bad request
    When I send a POST request to "/offers/a696ee71-d54f-4217-9592-c74eeae5a739/workspaces" with body:
    """
    not a json
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist

  @add_workspace.error_scenario
  Scenario: error_scenario, bad request
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offers/a696ee71-d54f-4217-9592-c74eeae5a739/workspaces" with body:
    """
    invalid json
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "invalid json body: Syntax error"

  @add_workspace.error_scenario
  Scenario: error_scenario, extra fields ...
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offers/1d567f4a-e3e0-464f-b87c-67f8e8fe7dce/workspaces" with body:
    """
    {
        "foo": "bar",
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a998"]
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors[0].path" should be equal to "payload[foo]"
    And the JSON node "errors[0].message" should be equal to "This field was not expected."

  @add_workspace.error_scenario
  Scenario: error_scenario, add workspaces on an unknown offer
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offers/b7462cb0-c168-4a12-aee6-8883ebc85a82/workspaces" with body:
    """
    {
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a998"]
    }
    """
    Then response status code should be 404
    And the JSON node "message" should be equal to "Not Found"
    And the JSON node "errors" should exist
    And the JSON node "errors[0].path" should be equal to "id"
    And the JSON node "errors[0].message" should be equal to "Offer with identifier `b7462cb0-c168-4a12-aee6-8883ebc85a82` does not exist."

  @add_workspace.error_scenario
  Scenario: error_scenario, add an unknown workspace
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offers/a696ee71-d54f-4217-9592-c74eeae5a739/workspaces" with body:
    """
    {
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a998", "055a5389-f070-4fda-81e0-82f1838cb42b"]
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors[0].path" should be equal to "payload[workspace_ids][1]"
    And the JSON node "errors[0].message" should be equal to "Workspace with identifier `055a5389-f070-4fda-81e0-82f1838cb42b` does not exist."

  @add_workspace.error_scenario
  Scenario: error_scenario, workspace uuid bad format
    Given I set "content-type" header equal to "application/json"
    When I send a POST request to "/offers/1d567f4a-e3e0-464f-b87c-67f8e8fe7dce/workspaces" with body:
    """
    {
        "workspace_ids": ["uuid", "50d1524a-1c37-4f9b-a6b7-5c611881e98b"]
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors[0].path" should be equal to "payload[workspace_ids][0]"
    And the JSON node "errors[0].message" should be equal to "This is not a valid UUID."

  @add_workspace.error_scenario
  Scenario: error_scenario, offer doesn't include all workspaces from type
    Given I should have a "offer" entity with identifier "a696ee71-d54f-4217-9592-c74eeae5a739"
    And I set "content-type" header equal to "application/json"
    When I send a POST request to "/offers/a696ee71-d54f-4217-9592-c74eeae5a739/workspaces" with body:
    """
    {
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a998"]
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors[0].message" should be equal to "Current Offer's workspaces must be referenced by OfferType `a696ee71-d54f-4217-9592-c74eeae5a895`."

  @add_workspace.error_scenario
  Scenario: error_scenario, offer node is already associated to a workspace from the payload
    Given I should have a "offer" entity with identifier "1d567f4a-e3e0-464f-b87c-67f8e8fe7dce"
    And I set "content-type" header equal to "application/json"
    When I send a POST request to "/offers/1d567f4a-e3e0-464f-b87c-67f8e8fe7dce/workspaces" with body:
    """
    {
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a998", "a696ee71-d54f-4217-9592-c74eeae5a997"]
    }
    """
    Then response status code should be 400
    And the JSON node "message" should be equal to "Bad Request"
    And the JSON node "errors" should exist
    And the JSON node "errors[0].message" should be equal to "Current Offer is already associated to a workspace from the payload."

  @add_workspace.happy_scenario
  Scenario: happy_scenario
    Given I should have a "offer" entity with identifier "1d567f4a-e3e0-464f-b87c-67f8e8fe7dce"
    And I set "content-type" header equal to "application/json"
    When I send a POST request to "/offers/1d567f4a-e3e0-464f-b87c-67f8e8fe7dce/workspaces" with body:
    """
    {
        "workspace_ids": ["a696ee71-d54f-4217-9592-c74eeae5a998", "a696ee71-d54f-4217-9592-c74eeae5a999"]
    }
    """
    Then response status code should be 204
    And I set "X-WORKSPACE-ID" header equal to "a696ee71-d54f-4217-9592-c74eeae5a997"
    When I send a GET request to "/offer/1d567f4a-e3e0-464f-b87c-67f8e8fe7dce.json"

    Then response status code should be 200
    And the JSON node "workspace_ids" should exist
    And the JSON node "workspace_ids" should have 3 elements
    And the JSON array node "workspace_ids" should contain "a696ee71-d54f-4217-9592-c74eeae5a997" element
    And the JSON array node "workspace_ids" should contain "a696ee71-d54f-4217-9592-c74eeae5a998" element
    And the JSON array node "workspace_ids" should contain "a696ee71-d54f-4217-9592-c74eeae5a999" element

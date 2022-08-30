
Feature: Content types feature

  Background: to Login with admin account and to go to Content section
    Given I am on the homepage
    When I click "Log in"
    And I fill in the following:
      |Username     |nikhilraut18     |
      |Password     |root             |
    And I press "Log in"
    Then I should see "My account"
    When I click "Content"

  @javascript
  Scenario: verify admin is able to add new content in Recipe content type
    When I visit "/node/add/recipe"
    And I press the button "URL alias"
    And I fill in the following:
      |Recipe Name            |Samosa       |
      |Preparation time       |30           |
      |Number of servings     |2            |
      |Difficulty             |hard         |
      |URL alias              |/samosa      |
    And I fill in CKEditor on field: "Summary" with :
    """
      A samosa is a fried or baked pastry with a savory filling, including ingredients such as spiced potatoes, onions, and peas. It may take different forms, including triangular, cone, or half-moon shapes, depending on the region.
    """
    And I fill in CKEditor on field: "Recipe instruction" with :
    """
    Hello This is Samosa recipe
    """
    And I upload "test.png" image in add media field
    And I press "Save"
    Then I should see "Recipe Samosa has been created."

  Scenario: verify admin is able to edit content in Recipe content type
    When I click "Edit" in the "Samosa" row
    And I fill in the following:
      |Preparation time       |40       |
      |Number of servings     |4        |
      |Difficulty             |easy     |

    And I press "Save"
    Then I should see " has been updated."

  Scenario: verify admin is able to delete content in Recipe content type
    When I click "Delete" in the "Samosa" row
    Then I should see "Are you sure you want to delete the content item Samosa?"
    When I press "Delete"
    Then I should see the text " has been deleted."

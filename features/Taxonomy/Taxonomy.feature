Feature: Taxonomy feature

  Background: to Login with admin account and to go to Taxonomy Section
    Given I am on the homepage
    When I click "Log in"
    And I fill in the following:
      |Username     |nikhilraut18     |
      |Password     |root             |
    And I press "Log in"
    Then I should see "My account"
    When I visit "admin/structure/taxonomy"
    And I click "List terms" in the "Recipe category" row

  @javascript
  Scenario: verify admin is able to add new term in taxonomy
    When I click "Add term"
    And I fill in the following:
      |Name       |Drinks     |
      |URL alias  |/drinks     |
    And I press "Save"
    Then I should see "Created new term Drinks."

  Scenario: verify admin is able to edit term in taxonomy
    When I click "Edit" in the "Drinks" row
    And I fill in "URL alias" with "/beverages/drinks"
    And I press "Save"
    Then I should see "Updated term"

  Scenario: verify admin is able to delete term in taxonomy
    When I click "Delete" in the "Drinks" row
    Then I should see "Are you sure you want to delete the taxonomy term Drinks?"
    When I press "Delete"
    Then I should see the text "Deleted term"

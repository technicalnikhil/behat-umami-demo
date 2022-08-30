Feature: Menu feature
  Background: to Login with admin account and to go to Main Navigation Menu Scetion
    Given I am on the homepage
    When I click "Log in"
    And I fill in the following:
      |Username     |nikhilraut18     |
      |Password     |root             |
    And I press "Log in"
    Then I should see "My account"
    When I visit "admin/structure/menu/manage/main"

    @javascript
 Scenario: verify admin is able to add new Menu link in Main Navigation Menu
    When I click "Add link"
    And I fill in the following:
     |Menu link title     |About Us                      |
     |Link                |http://localhost:8888/umami/  |
    And I press "Save"
    Then I should see the text "The menu link has been saved."

  Scenario: verify admin is able to edit Menu link in Main Navigation Menu
    When I click "Edit" in the "About Us" row
    And I fill in "Link" with "https://www.facebook.com"
    And I press "Save"
    Then I should see the text "The menu link has been saved."

  Scenario: verify admin is able to delete Menu link in Main Navigation Menu
    When I click "Delete" in the "About Us" row
    Then I should see the text "This action cannot be undone."
    When I press "Delete"
    Then I should see the text " has been deleted."

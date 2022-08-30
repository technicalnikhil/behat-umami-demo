Feature: Content types feature

  Background: to Login with admin account and to go to Content section
    Given I am on the homepage
    Then I click "Log in"
    When I fill in the following:
      |Username     |nikhilraut18     |
      |Password     |root             |
    And I press "Log in"
    Then I should see "My account"
    When I click "Content"
@javascript
  Scenario: verify admin is able to add new content in Basic page content type
    When I visit "/node/add/page"
    And I press the button "URL alias"
    When I fill in the following:
    |Title                 |Security News               |
    |URL alias             |/security_news              |
    And I press "Save"
    Then I should see "Basic page Security News has been created."

  Scenario: verify admin is able to edit content in Basic page content type
    When I click "Edit" in the "Security News" row
    And I fill in "Title" with "Hacking News"
    And I press "Save"
    Then I should see " has been updated."

  Scenario: verify admin is able to delete content in Basic page content type
    When I click "Delete" in the "Hacking News" row
    Then I should see "Are you sure you want to delete the content item Hacking News?"
    When I press "Delete"
    Then I should see the text " has been deleted."

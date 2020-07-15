Feature: In order to view my Account details,
    As an authenticated user,
    I should be able to access My account section

    Scenario: Verify user is able to view My account section on successful login
        Given I am on homepage
        And I follow "Log in"
        When I fill in "name" with "admin"
        And I fill in "pass" with "admin"
        And I press "Log in"
        Then I should see "My account"

    Scenario: Verify user is able to access HeroList
        Given I am on homepage
        And I follow "Herolist"
        Then I should see "Our Heroes"

    Scenario: Verify user is able to access HeroList
        Given I am on homepage
        Then I should see "Welcome to Drupal"
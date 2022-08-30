<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;

class FeatureContext extends RawDrupalContext {

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
  }


  /**
   * @When I wait :arg1 seconds
   */
  public function iWaitSeconds($arg1)
  {
    sleep($arg1);
  }

  /**
   * @When I enter following details :
   * @throws ElementNotFoundException
   */
  public function iEnterFollowingDetails(TableNode $table)
  {
    $page = $this->getSession()->getPage();


    $recipe_details = $table->getRowsHash();
    $page->fillField("Recipe Name",$recipe_details['Recipe Name']);
    $page->fillField("Preparation time",$recipe_details['Preparation time']);
    $page->fillField("Number of servings",$recipe_details['Number of servings']);
    $page->selectFieldOption("Difficulty", $recipe_details['Difficulty']);

    //$page->find('xpath', '//input[@value="Add media"]')->click();
    //sleep(5);
    //$page->find('xpath', '//input[@name="files[upload]"]')->attachFile($recipe_details['Media Image']);
    //$page->attachFileToField("files[upload]", $recipe_details['Media Image']);

    $page->fillField("Summary",$recipe_details['Summary']);

    $page->fillField("Recipe instruction",$recipe_details['Recipe instruction']);
  }

  /**
   * @When I fill in CKEditor on field: :arg1 with :
   * @throws Exception
   */
  public function iFillInCKEditorOnFieldWith2($locator, PyStringNode $string)
  {
    $el = $this->getSession()->getPage()->findField($locator);

    if (empty($el)) {
      throw new ExpectationException('Could not find WYSIWYG with locator: ' . $locator, $this->getSession());
    }

    $fieldId = $el->getAttribute('id');

    if (empty($fieldId)) {
      throw new Exception('Could not find an id for field with locator: ' . $locator);
    }

    $this->getSession()
      ->executeScript("CKEDITOR.instances[\"$fieldId\"].setData(\"$string\");");
  }

  /**
   * @Then I upload image to field
   */
  public function iUploadImageToField()
  {

  }
  /**
   * Wait for a specified time until an element is found.
   *
   * @param $selector -  Selectors like css path, xpath path, label etc.
   * @param string $type - Type of Mink Selector css, xpath, named etc.
   * @param int $wait - Max wait time.
   */
  public function waitForElement($selector, $type = 'css', $wait = 15) {
    // Wait until max timeout.
    while ($wait > 0) {
      // Check for Element.
      $element = $this->getSession()->getPage()->find($type, $selector);
      if (!is_null($element)) {
        return $element;
      }
      else {
        // Wait for 1 sec and continue.
        sleep(1);
        $wait--;
      }
    }
    return false;
  }

  public function waitForAjaxAndAnimation($wait = 30) {
    $this->getSession()
      ->wait($wait, "(jQuery.active === 0)");
  }

  /**
   * @Given /^I upload "([^"]*)" image in add media field$/
   */
  public function uploadImage($file) {

    //Click on Add media button
    $this->getSession()
      ->getPage()
      ->find('xpath',"//span[text()='Media Image']//../..//input[@id='edit-field-media-image-open-button']")
      ->click();

    $this->waitForElement('[name="files[upload]"]');

    //Upload the image
    if ($this->getMinkParameter('files_path')) {
      $fullPath = rtrim(realpath($this->getMinkParameter('files_path')), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$file;
      if (is_file($fullPath)) {
        $file = $fullPath;
      }
    }

    $this->getSession()
      ->getPage()
      ->attachFileToField('files[upload]', $file);

    $this->waitForElement('[name="media[0][fields][name][0][value]"]');

    //Enter Alternative text
    $this->getSession()
      ->getPage()
      ->find('xpath',"//label[text()='Alternative text']//parent::div//input[@name='media[0][fields][field_media_image][0][alt]']")
      ->setValue('demoimage');

    //Click on Save
    $this->getSession()
      ->getPage()
      ->find('xpath', "//button[text()='Save']")
      ->click();

    $this->waitForAjaxAndAnimation();

    //Click on insert selected button
    $this->waitForElement('//div[@class="ui-dialog-buttonset form-actions"]//button[text()="Insert selected"]', "xpath")
      ->click();

    $this->waitForAjaxAndAnimation();
  }
  /**
   * @Then I press the button :arg1
   */
  public function iPressTheButton($button)
  {
    $page = $this->getSession()->getPage();
    $page->find('xpath', '//summary[text()="'.$button.'"]')->click();
  }
  /**
   * @Then I should see default status as :arg1
   */
  public function iShouldSeeValueAs($arg1)
  {
    $page = $this->getSession()->getPage();
    $text=$page->find('xpath', '//select[@name="moderation_state[0][state]"]/option[@selected="selected"]')->getText();
    assertEquals("Draft",$text);
  }

}


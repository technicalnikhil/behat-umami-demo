<?php
<<<<<<< HEAD
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
=======


use Behat\Mink\Exception\ElementNotFoundException;
use Drupal\DrupalExtension\Context\MinkContext;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
>>>>>>> 8c013b8 (First umami behat test script)
use function PHPUnit\Framework\assertEquals;

/**
 * Defines application features from the specific context.
 */
<<<<<<< HEAD
class FeatureContext extends RawMinkContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {

    }

    /**
     * @When I wait :arg1 seconds
     */
    public function iWaitSeconds($arg1)
    {
        sleep($arg1);
    }

    /**
     * @Then I should see Product by A to Z sorting order
     */
    public function iShouldSeeProductByAToZSortingOrder()
    {
        $page = $this->getSession()->getPage();
        $element = $page->findAll('xpath', '//ul[@class="product_list grid row"]/li/div/div[2]/h5/a');
        $sorted = $element;
        sort($sorted);
        assertEquals($sorted, $element, "Not matches with expected");
    }

    /**
     * @Then I should see Product by Z to A sorting order
     */
    public function iShouldSeeProductByZToASortingOrder()
    {
        $page = $this->getSession()->getPage();
        $element = $page->findAll('xpath', '//ul[@class="product_list grid row"]/li/div/div[2]/h5/a');
        $sorted = $element;
        rsort($sorted);
        assertEquals($sorted, $element, "Not matches with expected");
    }

    /**
     * @Then I should see Product by Lowest first sorting order
     */
    public function iShouldSeeProductByLowestFirstSortingOrder()
    {
        $page = $this->getSession()->getPage();
        $element = $page->findAll('xpath', '//ul[@class="product_list grid row"]/li/div/div[2]/div[1]/span');
        $sorted = $element;
        sort($sorted);
        assertEquals($sorted, $element, "Not matches with expected");
    }

    /**
     * @Then I should see Product by Highest first sorting order
     */
    public function iShouldSeeProductByHighestFirstSortingOrder()
    {
        $page = $this->getSession()->getPage();
        $element = $page->findAll('xpath', '//ul[@class="product_list grid row"]/li/div/div[2]/div[1]/span');
        $sorted = $element;
        rsort($sorted);
        assertEquals($sorted, $element, "Not matches with expected");
    }

    /**
     * @When I click on :arg1
     */
    public function iClickOn($product_name)
    {
        //$this->getSession()->maximizeWindow();
        $page = $this->getSession()->getPage();

        //$product=$page->find('xpath', '//a[@class="product_img_link" and @title=“'.$product_name.'“]');
        $product=$page->find('xpath', '//a[@title="'.$product_name.'"]/parent::div/child::a[@class="product_img_link"]');
        $product->click();
    }

    /**
     * @Then I verify cart details for the product :arg1
     */
    public function andIVerifyCartProductDetails($product_name,TableNode $table)
    {
        $array=$this->function1($product_name);    //To get back actual color, size and quantity
        $actual_color=$array[0];
        $actual_size=$array[1];
        $actual_quantity=$array[2];

       foreach ($table as $row)
       {
        $quantity=$row['Quantity'];
        $size=$row['Size'];
        $color=$row['Color'];

        assertEquals($quantity, $actual_quantity, "Quantity not matches");
        assertEquals($size, $actual_size, "Size not matches");
        assertEquals($color, $actual_color, "Color not matches");
       }

    }
    /**
     * @Then I verify total cost
     */
    public function iVerifyTotalCost()
    {

        $array=$this->function2();   //to get back actual and expected sum of products
        $sum=$array[0];
        $total_products=$array[1];

        assertEquals($sum, $total_products, "Total Price Not matches with expected");

        $array=$this->function3();   //to get back actual and expected total after shipping charges
        $total_shipping=$array[0];
        $total_without_tax=$array[1];

        assertEquals($total_without_tax, $total_products+$total_shipping, "Price before tax Not matches with expected");

        $array=$this->function4();   // to get back actual and expected total after taxes
        $tax=$array[0];
        $final_total=$array[1];

        assertEquals($final_total, $total_without_tax+$tax, "Final Total Price Not matches with expected");
    }
    /**
     * @Then I edit quantity as :arg1 for product :arg2
     */
    public function iEditQuantityAsForProduct($quantity, $product)
    {
        $page = $this->getSession()->getPage();
        $temp = $page->find('xpath', '//a[text()="'.$product.'"]/ancestor::tr/td[@class="cart_quantity text-center"]/input[2]');
        $temp->setValue($quantity);
    }
    /**
     * @When I delete product :arg1
     */
    public function iDeleteProduct($product)
    {
        $page = $this->getSession()->getPage();
        $page->find('xpath', '//a[text()="'.$product.'"]/ancestor::tr/td[@class="cart_delete text-center"]')->click();
    }

    public function function1($product_name): array
    {
        $page = $this->getSession()->getPage();
        $product_row  = $page->find('xpath', '//a[text()="'.$product_name.'"]/ancestor::tr');

        $temp=$product_row->find('xpath', '//descendant::small/a')->getText();
        $temp2=explode(', ',$temp);

        $color=explode(' : ',$temp2[0])[1];
        $size=explode(' : ',$temp2[1])[1];
        $quantity=$product_row->find('xpath', '//td[@class="cart_quantity text-center"]/input[1]')->getAttribute("value");

        return array($color, $size, $quantity);
    }

    public function function2(): array
    {
        $page = $this->getSession()->getPage();
        $sum=0;
        $unit_prices  = $page->findAll('xpath', '//table[@id="cart_summary"]/tbody/tr/td[@class="cart_unit"]/span/span[1]');
        for ($i = 0; $i<count($unit_prices); $i++) {

            $unit_price=floatval(explode('$',$unit_prices[$i]->getText())[1]);
            $temp2= $unit_prices[$i]->find('xpath', '//ancestor::td/following-sibling::td[@class="cart_quantity text-center"]/input[1]');
            $quantity=intval($temp2->getAttribute("value"));
            $expected_total=$unit_price*$quantity;
            $j=$i+1;
            $temp = $page->find('xpath', '//table[@id="cart_summary"]/tbody/tr['.$j.']/td[@class="cart_total"]/span');
            $actual_total=floatval(explode('$',$temp->getText())[1]);
            $p_name = $page->find('xpath', '//table[@id="cart_summary"]/tbody/tr['.$j.']/td[@class="cart_description"]/p/a')->getText();
            assertEquals($expected_total, $actual_total, "Total Price Not matches with expected for the product '$p_name'");

            $sum=$sum+$actual_total;
        }
        $temp = $page->find('xpath', '//tr[@class="cart_total_price"]/td[@id="total_product"]');
        $total_products=floatval(explode('$',$temp->getText())[1]);
        return array($sum, $total_products);
    }

    public function function3(): array
    {
        $page = $this->getSession()->getPage();

        $temp = $page->find('xpath', '//tr[@class="cart_total_delivery"]/td[@class="price"]');
        $total_shipping=floatval(explode('$',$temp->getText())[1]);

        $temp = $page->find('xpath', '//tr[@class="cart_total_price"]/td[@id="total_price_without_tax"]');
        $total_without_tax=floatval(explode('$',$temp->getText())[1]);

        return array($total_shipping, $total_without_tax);
    }

    public function function4(): array
    {
        $page = $this->getSession()->getPage();

        $temp = $page->find('xpath', '//tr[@class="cart_total_tax"]/td[@id="total_tax"]');
        $tax=floatval(explode('$',$temp->getText())[1]);

        $temp = $page->find('xpath', '//tr[@class="cart_total_price"]/td[@id="total_price_container"]/span');
        $final_total=floatval(explode('$',$temp->getText())[1]);

        return array($tax, $final_total);
    }
    /**
     * @Then I click on the button :arg1
     */
    public function iClickOnTheButton($button)
    {
        $page = $this->getSession()->getPage();

        $page->find('xpath', '//span[text()="'.$button.'"]/parent::a')->click();
    }

}
=======
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
>>>>>>> 8c013b8 (First umami behat test script)

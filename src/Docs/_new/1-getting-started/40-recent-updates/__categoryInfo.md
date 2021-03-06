[titleEn]: <>(Recent updates)
[__RAW__]: <>(__RAW__)

<p>Here you can find recent information about technical updates and news regarding <a href="https://github.com/shopware/platform">shopware platform</a>.</p>

<p><strong>New: Our public admin component library for easy scaffolding of your admin modules</strong></p>

<p><a href="https://component-library.shopware.com/">https://component-library.shopware.com</a></p>

<h2>May 2019</h2>

<h3>2019-05-13: Added RequestDataBag to interface of payment handler</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The <code>\Shopware\Core\Framework\Validation\DataBag\RequestDataBag</code> was added to the pay methods of
<code>\Shopware\Core\Checkout\Payment\Cart\PaymentHandler\SynchronousPaymentHandlerInterface</code> and
<code>\Shopware\Core\Checkout\Payment\Cart\PaymentHandler\AsynchronousPaymentHandlerInterface</code>.
With this, you are able to send custom parameter into the payment handling.</p>
<h3>2019-05-06: Split setting UI in categories</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The Settings are now split up in the categories <code>Shop</code>, <code>System</code> and <code>Plugins</code>.</p>
<p>To add your setting to the different categories you just have to extend the right template block.</p>
<p>Instead of the old <code>sw_settings_content_card_slot_default</code> block you now have three specific blocks for each category:</p>
<p><code>sw_settings_content_card_slot_shop</code></p>
<p><code>sw_settings_content_card_slot_system</code></p>
<p><code>sw_settings_content_card_slot_plugins</code></p>
<h3>2019-05-06: New E2E commands for select components</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>There are two new custom E2E commands for the new <code>sw-single-select</code> and <code>sw-multi-select</code> components:</p>
<pre><code>.fillMultiSelect(
    '.selector', 
    'Search term', 
    'Value'
);

.fillSingleSelect(
    '.selector', 
    'Value', 
    1 /* Desired result position */
);</code></pre>
<p>Those are also valid when using the <strong>entity</strong> select components.</p>
<h3>2019-05-03: Changes in sw-field component</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The <code>sw-field</code> component got a complete overhaul in order to remove unused properties, doubled configuration
and a lot of unnecessary template and logic inheritance.</p>
<h3>New structure for fields</h3>
<p>The <code>sw-field</code> component now uses slot mechanics and property consumption instead of component inheritance.</p>
<p>If you inspect an <code>sw-field</code> you may notice that it has several child components depending on your fields type:</p>
<pre><code class="language-XML">&lt;sw-field&gt;
    &lt;sw-contextual-field&gt;
        &lt;sw-block-field&gt;
            &lt;sw-base-field&gt;</code></pre>
<p>This child components consume basic information for example <code>label</code> and <code>size</code> properties, apply them to their own
elements and expose them back to you via slots.</p>
<h3>Remove properties, types and components</h3>
<ul>
<li>The <code>sw-number-field</code> now changes its model on 'change' instead of input. This makes it easier to validate numbers</li>
<li><code>sw-fiel-addition</code>: Was removed. Its purpose was to style the prefix and suffix sections of <code>sw-field</code></li>
<li><code>sw-field-label</code>: Was removed. </li>
<li><code>sw-field-help-text</code>: We removed the <code>sw-field-help-text</code> because it wasn't really used in the project.
Also this old grey description text did not comply with our design system any more.</li>
<li><code>tooltipText</code>: Having two properties for <code>helpText</code> and <code>tooltipText</code> could be confusing
so we removed the  property in favor of <code>helpText</code>. The <code>helpText</code> property is now rendered in an <code>sw-help-text</code> bubble.</li>
<li><code>type="bool"</code>: It was very confusing to have to two switch typed variants of <code>sw-field</code> (<code>type="switch"</code> and <code>type="bool"</code>)
which' only difference was a border. We removed <code>&lt;sw-field type="bool"&gt;</code> an replace it with an <code>bordered</code> attribute.
<pre><code class="language-HTMl">
&lt;sw-field type="switch" borderd [...] &gt;&lt;/sw-field&gt;</code></pre></li>
</ul>
<pre><code>* `prefix` and `suffix`: We removed the `prefix` and `suffix` properties in all fields in favour of using slots instead.
This change removes the possibility to define two different values. With Vue.js' new slot syntax defining prefixes and suffixes is easy:
```HTML
&lt;sw-fiel type="text" [...]&gt;
    &lt;template #prefix&gt;
      {{ dynamicPrefix }}
    &lt;/template&gt;

    &lt;template #suffix&gt;
        constant
    &lt;/template&gt;
&lt;/sw-fiel&gt;
</code></pre>
<h3>Added properties</h3>
<ul>
<li>size (String): Got new values <code>'medium'</code> and <code>small</code>. Also the the sizes of the input fields changed to <code>small</code> = 32px
<code>medium</code> = 40px and <code>default</code> = 48px height.</li>
<li>type=&quot;switch&quot; got a new prop <code>bordered</code> (Boolean) that made <code>type="boolean"</code> obsolete.</li>
<li>type=&quot;select&quot; got a new prop <code>aside</code> (Boolean) to set the label left to the select box.</li>
</ul>
<h3>&quot;Strict types&quot; in properties</h3>
<p>We removed the <code>sw-inline-snippet</code> mixin from all <code>sw-fields</code> and set the translated properties (e.g. <code>label</code>) to type string.
That means you can't pass objects with translations anymore. </p>
<h3>sw-base-field</h3>
<p>The <code>sw-base-field</code> component consumes basic information about your form field and is intended to display a basic header and error information. </p>
<h4>Props</h4>
<ul>
<li>name (String): Use this to override the exposed identifier  </li>
<li>label (String): The label of the component </li>
<li>helpText (String): The help text is displayed as an <code>sw-help-text</code> component on the top right corner of the field </li>
<li>error/errorMessage (String, Object): Don't mind them now as they will be refactored soon</li>
<li>disabled (Boolean): Sets the disabled State</li>
<li>required (Boolean): (not fully implemented yet)</li>
<li>inherited (Boolean): (not fully implemented yet)</li>
</ul>
<h4>Slots</h4>
<ul>
<li><code>sw-field-input</code> scope: { identification, disabled }
<ul>
<li>identification (String): If name property is set this will be just the given name. If not this the value is <code>sw-field--${Uuid}</code>.
which can be used to link elements that have an <code>for</code> attribute to your inputs.</li>
<li>disabled (Boolean): You may need the information in your slot since you actually do not want to have an own disabled prop.</li>
</ul></li>
</ul>
<h3>sw-block-field</h3>
<p>The main purpose of the <code>sw-block-field</code> component is intended to take care of sizes borders and border colors</p>
<h4>Props</h4>
<ul>
<li>size (String): Defines the can be either 'small', 'medium', or 'default' </li>
</ul>
<h4>Slots</h4>
<ul>
<li><code>sw-field-input</code> scope: { identification, disabled, swBlockSize, setFocusClass, removeFocusClass }
<ul>
<li>identification, disabled are just exposed from <code>sw-base-field</code></li>
<li>swBlockSize (String): A CSS selector that can be used by your component to react to different sizes</li>
<li>setFocusClass, removeFocusClass (function): You can use this in your component to set or remove the focuses state of an <code>sw-block-field</code></li>
</ul></li>
</ul>
<p>(e.g. bind it to the click of a button)</p>
<h3>sw-contextual-field</h3>
<p>The <code>sw-contextual-field</code> is intended to render a &quot;context&quot; to the field. This context is displayed as pre and suffix.</p>
<h4>Props</h4>
<ul>
<li>None</li>
</ul>
<h4>Slots</h4>
<ul>
<li><code>sw-contextual-field-prefix</code> scope: { disabled, identification }</li>
<li><code>sw-contextual-field-suffix</code> scope: { disabled, identification }</li>
<li><code>sw-field-input</code> scope: { identification, error, disabled, swBlockSize, setFocusClass, removeFocusClass, hasSuffix, hasPrefix }
<ul>
<li>identification, error, disabled, swBlockSize, setFocusClass, removeFocusClass - as described above</li>
<li>hasSuffix, hasPrefix: selfexplaining</li>
</ul></li>
</ul>
<h3>Follow ups</h3>
<ul>
<li>In the next few days we will apply the <code>sw-field</code> structure to <code>sw-media-field</code>
and <code>sw-select</code>.</li>
<li>Errorhandling and fieldvalidation are in progress</li>
<li>integrate inherited values</li>
</ul>
<h3>2019-05-03: Product streams</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>Product streams are now released. With this feature you can filter products based on DAL fields in the admin and via API.</p>
<p>The filters start from the product entity and can be restricted for the admin with a blacklist.</p>
<p>This blacklist can be found in the module <code>app/service/product-stream-condition</code>. There you can add blacklist keywords for general or entity based purpose.</p>
<p>With a <code>ServiceProviderDecorator</code> you can extend the blacklists for the admin view with a plugin. An rule-builder based implementation can be found here: <code>platform/src/Administration/Resources/administration/src/app/decorator/condition-type-data-provider.js</code>.</p>
<p>If you extend the DAL, please check the admin for a possible new restriction with the blacklists. If the new DAL field should be used in the product streams, then translate the field. The translations can be found here: <code>platform/src/Administration/Resources/administration/src/module/sw-product-stream/snippet</code>.</p>
<h3>2019-05-03: Database debugging</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<pre><code class="language-bash">
&gt;  bin/console database:generate-debug-views
</code></pre>
<p>Execute this command to create views in the database that replace all binary ids with hex values. All these views are named <code>debug_ORIGINAL_TABLE_NAME</code>.</p>
<h4>Example</h4>
<p>Executing <code>SELECT * FROM debug_tax</code> will result in:</p>
<pre><code>+----------------------------------+----------+------+---------------+-------------------------+------------+
| id                               | tax_rate | name | custom_fields | created_at              | updated_at |
+----------------------------------+----------+------+---------------+-------------------------+------------+
| 12cb17bab0264ae4a518c0e053146a9c |    20.00 | 20%  | NULL          | 2019-05-03 12:04:10.000 | NULL       |
| 1eceb1547afe476ca39d49ca6a9c0047 |     5.00 | 5%   | NULL          | 2019-05-03 12:04:10.000 | NULL       |
| 6a456bf51f0b4a7c8655de754372be59 |     7.00 | 7%   | NULL          | 2019-05-03 12:04:10.000 | NULL       |
| 9015c672349c43f88c1143f6e382f52d |    19.00 | 19%  | NULL          | 2019-05-03 12:04:10.000 | NULL       |
| c081e2c696d04426840073a925634914 |     1.00 | 1%   | NULL          | 2019-05-03 12:04:10.000 | NULL       |
+----------------------------------+----------+------+---------------+-------------------------+------------+</code></pre>
<h3>2019-05-02: Rule scope in administration</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The rule scope of rules are now supported and required to define in the administration.</p>
<p>The scopes filter the matching rules so it's possible to show only <code>cart</code> or <code>lineItem</code> based rules.</p>
<p>The scopes can be added in the in the condition type data provider. See <code>platform/src/Administration/Resources/administration/src/app/decorator/condition-type-data-provider.js</code></p>
<p>At the moment, these types are supported</p>
<ul>
<li><code>global</code> --&gt; used for rules which has no restriction (like <code>DateRangeRule</code>)</li>
<li><code>cart</code> --&gt; used for rules which require the CartRuleScope (like <code>CartAmountRule</code>)</li>
<li><code>checkout</code> --&gt; used for rules which require the CheckoutRuleScope (like <code>LastNameRule</code>)</li>
<li><code>lineItem</code> --&gt; used for rules which require the LineItemScope (like <code>LineItemTagRule</code>)</li>
</ul>
<h3>2019-05-02: Remove static methods from EntityDefinition</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We just removed a lot of static calls from DataAbstractionLayer. All <code>EntityDefinitions</code> are now instances provided through the container. This changed a lot of internals and a few but <strong>breaking</strong> public API methods.</p>
<p>New rule of thumb: <strong>If you need something from the <code>EntityDefinition</code> inject it</strong></p>
<h3>EntityDefinition</h3>
<p>The <code>EntityDefinition</code> now must not contain any static methods.</p>
<pre><code class="language-php"> public static function getEntityName() { ... }</code></pre>
<p>Is now invalid and will throw a compile error from php. This now must be called</p>
<pre><code class="language-php">public function getEntityName() {... }</code></pre>
<p>Please adjust all method calls accordingly. (Usually <code>getEntityName</code>, <code>defineFields</code>, <code>getCollectionClass</code>, <code>getEntityClass</code>)</p>
<h3>EntityDefinition service declaration</h3>
<p>The service definition tags already had a <code>entity="entity_name""</code> property. From now on this is required and the build will fail if it's not provided.</p>
<pre><code class="language-xml">&lt;service id="Shopware\Core\Checkout\Promotion\Aggregate\PromotionDiscountRule\PromotionDiscountRuleDefinition"&gt;
    &lt;tag name="shopware.entity.definition" entity="promotion_discount_rule"/&gt;
&lt;/service&gt;</code></pre>
<h3>EntityRepository</h3>
<p>The EntityRepositoryInterface now expects an instance of a Definition - as well as all subsequent Classes (Write, Read, ...). In order to ease the migration the repository now has a <code>getDefinition()</code> method to return the repositories definition if inspection or result rendering is necessary.</p>
<h3>ResponseFactoryInterface</h3>
<p>All responses from custom actions rely on Entity definitions. Now you need to provide an instance of the EntityDefinition. You should be able to use the Repository you injected for most instances.</p>
<pre><code class="language-php">/**
 * @var EntityRepositoryInterface
 */
private $orderRepository;

public function __construct(EntityRepositoryInterface $orderRepository) 
{
    $this-&gt;orderRepository = $orderRepository;
}</code></pre>
<p>Calls to the <code>ResponseFactoryInterface</code> will now look like this:</p>
<pre><code class="language-php">return $responseFactory-&gt;createDetailResponse(
    $orders,
    $this-&gt;orderRepository-&gt;getDefinition,
    $request,
    $context-&gt;getContext()
);</code></pre>
<h3>::class references</h3>
<p>The dependency injection container secures that a particular instance of a definition is created only once per request. If you need equality checks please use the strict comparison operator on the objects themselves. <code>$assiciation-&gt;getReferenceDefinition() === $definition</code> works and is the recommended way.</p>
<h3>SalesChannel-API</h3>
<p>The SalesChannel-API's implementation got revamped. Since the definitions themselves are instances now, the SalesChannel-API is now an entirely second cluster of instances in memory that does not touch the original instances. By that we removed the <code>SalesChannelDefinitionTrait</code> and calls to <code>decorateDefinitions</code> are no longer available. Everything will be injected automatically through the container at compile time.</p>
<p>This is achieved through a decorated registry. Object comparsion with the <code>SalesChannelDefinitionInstanceRegistry</code> yields different results from the base registry. </p>
<pre><code class="language-php">// Sales channle entities are different classes
$salesChannelProductDefinition instanceof $sproductDefinition // === true
$productDefinition instanceof $salesChannelProductDefinition // === false

// The decorated registry allways returns the sales channel object, regardless of the provided service id
$salesChannelRegistry-&gt;get(ProductDefinition::class) instanceof $slaesChannelRegistry-&gt;get(SalesChannelProductDefinition::class) // == true
$salesChannelRegistry-&gt;get(ProductDefinition::class) === $slaesChannelRegistry-&gt;get(SalesChannelProductDefinition::class) // == true</code></pre>
<p>This replacement is done based on the <code>entityName</code> so overwriting a base definition takes a service declaration like this:</p>
<pre><code class="language-xml">&lt;service id="Shopware\Core\Content\Product\SalesChannel\SalesChannelProductDefinition"&gt;
    &lt;tag name="shopware.sales_channel.entity.definition" entity="product"/&gt;
&lt;/service&gt;</code></pre>
<p>Overwrites the product definition in SalesChannel-API requests.</p>
<h3>Internal changes</h3>
<p>The change to instances brought many changes to internal implementations in the DataAbstractionLayer. Conceptually the whole configuration now relies on a <strong>compile step</strong> that is automatically triggered by the container on object creation.</p>
<p>All Definitions and Fields now refer to concrete instances of EntityDefinition as opposed to the class reference. By that you can navigate the entire Object tree without using any registry or container calls:</p>
<pre><code class="language-php">$productDefinition // ProductDefinition
    -&gt;getFields() // CompiledFieldCollection
    -&gt;get('categories') // ManyToManyAssociationField
    -&gt;getToManyDefinition() // CategoryDefinition
    -&gt;getFields() // CompiledFieldCollection
    -&gt;get('tags') // ManyToManyAssociationField
    -&gt;getToManyDefinition() // TagDefinition</code></pre>
<h4>Removal of *Registries</h4>
<p>The formerly necessary <code>FieldSerializerRegistry</code>, <code>FieldAccessorBuilderRegistry</code> and <code>FieldResolverRegistry</code> were removed in favor of getters on the field class. The fields lazily acquire these objects from the service container through the <code>DefinitionInstanceRegistry</code>.</p>
<pre><code class="language-php">class Field
{
    ...

    public function getSerializer(): FieldSerializerInterface { ... }

    public function getResolver(): ?FieldResolverInterface { ... }

    public function getAccessorBuilder(): ?FieldAccessorBuilderInterface { ... }

    ...
}</code></pre>
<h3>2019-05-02: Adding the process button component</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>A new component called <code>sw-button-process</code> was added to Shopware platform.
The button is introduced to display the status of the process the button should start. E.g. if you click the button
to save an entity, it will display a loading indicator while the save process is running and a tick icon if the
process was finished successfully. This way, we tend to get rid of those &quot;Success&quot; notifications which does not
provide any other useful information.</p>
<p><strong>Usage</strong></p>
<p>The <code>sw-button-process</code> component looks as stated below:</p>
<pre><code class="language-$html">&lt;sw-button-process
        class="sw-product-detail__save-action"
        :isLoading="isLoading"
        :processSuccess="isSaveSuccessful"
        :disabled="isLoading"
        variant="primary"
        @process-finish="saveFinish"
        @click="onSave"&gt;
        {{ $tc('sw-cms.detail.labelButtonSave') }}
&lt;/sw-button-process&gt;</code></pre>
<p>As you can see, you can use the <code>sw-button-process</code> component similar as you're used to with <code>sw-button</code>.
We just need some further information:</p>
<ul>
<li><code>isLoading</code>: Necessary to indicate the time when the process is currently running.</li>
<li><code>processSuccess</code>: This prop signalizes if the process was finished successfully, so that the <code>sw-button-process</code>
button can start its success animation.</li>
</ul>
<p>If you want to use the <code>sw-button-process</code> button, you need to change those props accordingly to your module's behavior.</p>
<p><strong><em>Events and creation as edge case</em></strong></p>
<p>The success animation needs 1.25 seconds to run per default. This way, the <code>create</code> pages were a difficulty since
they reload the whole page including the process button, interrupting the animation in the process. For this reason,
we use the <code>@process-finish</code> event to signalize that the save process is finished. In a create page, you need to
override this event to navigate to the detail page after the animation ran. As seen in the example below,
you just need to move the routing to the <code>saveFinish</code> event to make it run:</p>
<pre><code class="language-$javascript">saveFinish() {
    this.isSaveSuccessful = false;
    this.$router.push({ name: 'sw.cms.detail', params: { id: this.page.id } });
},

onSave() {
    this.$super.onSave();
}</code></pre>
<h2>April 2019</h2>

<h3>2019-04-30: Removing the "X-" header prefix</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The leading &quot;X-&quot; in a header name has been deprecated for years (<a href="https://tools.ietf.org/html/rfc6648">https://tools.ietf.org/html/rfc6648</a>) and therefore should not be used anymore.</p>
<p><strong>Before:</strong></p>
<ul>
<li><code>x-sw-context-token</code></li>
<li><code>x-sw-access-key</code></li>
<li><code>x-sw-language-id</code></li>
<li><code>x-sw-inheritance</code></li>
<li><code>x-sw-version-id</code></li>
</ul>
<p><strong>After:</strong></p>
<ul>
<li><code>sw-context-token</code></li>
<li><code>sw-access-key</code></li>
<li><code>sw-language-id</code></li>
<li><code>sw-inheritance</code></li>
<li><code>sw-version-id</code></li>
</ul>
<h3>2019-04-30: Refactored plugin entity</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We refactored the plugin entity as follows:</p>
<ul>
<li>Renamed plugin.name =&gt; plugin.baseClass
<ul>
<li>The plugin.baseClass property holds the fully qualified domain name (FQDN)</li>
</ul></li>
<li>Added plugin.name
<ul>
<li>The plugin.name holds the technical plugin name</li>
</ul></li>
</ul>
<h3>2019-04-30: Notification center</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The administration now has a notification center in every <code>sw-page</code>.
This is not a breaking change, you can still make notifications the same way as before.
But there are some nice new Features that you may want to know:</p>
<h3>The notification mixin</h3>
<p>Currently there is a <code>notification.mixin.js</code> which only abstracts the store and sets a notification <code>variant</code>, depending on the method you call.
It is recommended to use the store directly instead to create or update notifications (because of the update functionality).</p>
<h3>Store functionality</h3>
<p>You can create notficiations the following way:</p>
<pre><code>this.$store.dispatch('notification/createNotification', {
    title: 'My title',
    message: 'My message',
    variant: 'info'
}).then((notificationId) =&gt; {
    // Save the id to modify the notification in the future.
});</code></pre>
<p>And you can update your notification the following way:</p>
<pre><code>this.$store.dispatch('notification/updateNotification', {
    uuid: mySavedNotificationId
    message: 'changed message'
}).then((notificationId) =&gt; {
    // The notification id stays the same here
});</code></pre>
<p>The update action is very flexible. You can for example set <code>growl: true</code> to show the user a growl message (again).
You can also set <code>visited: false</code> to mark the notification as not seen by the user.
There is also a way to set the <code>visited</code> parameter dynamically depending on data changes (have a look at the <code>metadata</code> parameter).
If the user deletes the notification and you update it, it will be recreated with the default values and your specified values.</p>
<h3>Possible notification parameters</h3>
<ul>
<li><code>title</code> <strong>required</strong> -&gt; The title of the notification.</li>
<li><code>message</code> <strong>required</strong> -&gt; The text of the notification.</li>
<li><code>variant</code> <strong>recommended</strong> -&gt; The styling of the notification. Possible values are <code>success</code>, <code>info</code>, <code>warning</code> and <code>error</code>. If set to <code>success</code> the notification will be growl only. The default value is <code>info</code>.</li>
<li><code>system</code> <strong>optional</strong> -&gt; Applies also to the styling of the notification. If set to true it will be darker. The default is <code>false</code>.</li>
<li><code>autoClose</code> <strong>optional</strong> -&gt; If set to true the growl notification will close after the specified <code>duration</code>. The default is <code>true</code>.</li>
<li><code>duration</code> <strong>optional</strong> -&gt; The duration of the growl message in ms. The default is <code>5000</code></li>
</ul>
<p>New parameters</p>
<ul>
<li><code>growl</code> <strong>recommended</strong> -&gt; Show the notification as a growl message. It will also be in the notification center. The default is <code>true</code>, but you should consider setting this to <code>false</code> to not overwhelm the user in notifications.</li>
<li><code>visited</code> <strong>optional</strong> -&gt; If set to false, the notification is mark as not seen by the user and will be displayed so. The default is <code>false</code>.</li>
<li><code>isLoading</code> <strong>optional</strong> -&gt; Shows a loading indicator if set to true. Also the notification will not be saved if it is set to <code>true</code>. If The default is <code>false</code></li>
<li><code>metadata</code> <strong>optional</strong> -&gt; You can store a object here. If the object is different from the already attached one, the notification will automatically set <code>visited</code> to false (as long as not other specified). This is useful to show a progress in the notification where you want to notify the user about progress changes.</li>
</ul>
<h3>2019-04-30: New ManyToManyIdField</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The new <code>\Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyIdField</code> allows to store ids of an <code>\Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField</code> inside the entity.</p>
<h2>How to implement</h2>
<ol>
<li>
<p>Add the field to the entity definition class:</p>
<p><code>new ManyToManyIdField('property_ids', 'propertyIds', 'properties')</code></p>
<p>The third parameter has to be the property name of the related association</p>
<p><code>(new ManyToManyAssociationField('properties', ...</code></p>
</li>
<li>
<p>Add property, getter and setter to entity class</p>
<pre><code>
/**
 * @var array|null
 */
protected $propertyIds;

public function getPropertyIds(): ?array
{
    return $this-&gt;propertyIds;
}

public function setPropertyIds(?array $propertyIds): void
{
    $this-&gt;propertyIds = $propertyIds;
}</code></pre>
</li>
</ol>
<pre><code>
The DAL will detect this field automatically and updates the data each time the entity changed.

## When do i really need this field?

This field is required for a special kind of filter. The above example shows the relation between a `product` and its `properties`.
Adding this field to the product definition allows to send the following requrest to the DAL:

**select all products which has the property `red` or `green` AND `xl` or `l`**
</code></pre>
<p>$criteria = new Criteria();
$criteria-&gt;addFilter(
new EqualsAnyFilter('product.propertyIds', ['red-id', 'green-id'])
);
$criteria-&gt;addFilter(
new EqualsAnyFilter('product.propertyIds', ['xl-id', 'l-id'])
);</p>
<pre><code></code></pre>
<h3>2019-04-30: Attributes are now custom fields</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We have got the feedback that the intended usage of attributes is unclear
and often mistaken for product properties. So we decided to rename
attributes to custom fields.</p>
<h2>What do I have to do?</h2>
<p>If you've used <code>AttributesField</code> in  definitions, you need to replace
it with <code>CustomFields</code> and rename the column <code>attributes</code> to <code>custom_fields</code>.
Alternatively, you can override the <code>storageName</code> in the <code>CustomFields</code> constructor.</p>
<h3>2019-04-29: New shortcut methods for transaction state changes</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>There is now a new class which can help you to deal with the <code>StateMachine</code>.
<code>Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStateHandler</code> contains some methods to improve the readability of your code when changing the state.</p>
<p>old way:</p>
<pre><code>public function example()
{
    $completeStateId = $this-&gt;stateMachineRegistry-&gt;getStateByTechnicalName(
        OrderTransactionStates::STATE_MACHINE,
        OrderTransactionStates::STATE_PAID,
        $context
    )-&gt;getId();

    $data = [
        'id' =&gt; $transaction-&gt;getOrderTransaction()-&gt;getId(),
        'stateId' =&gt; $completeStateId,
    ];

    $this-&gt;transactionRepository-&gt;update([$data], $context);
}</code></pre>
<p>new way:</p>
<pre><code>public function example()
{
    $transactionStateHandler = $this-&gt;getContainer()-&gt;get(OrderTransactionStateHandler::class);
    $transactionStateHandler-&gt;complete($transaction-&gt;getOrderTransaction()-&gt;getId(), $context);
}</code></pre>
<h3>2019-04-29: CreatedAt and UpdatedAt are set as default</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>By extending the EntityDefinition-Class all Definitions now automatically have a <code>cratedAt</code>- and <code>UpdatedAt</code>-Field, so you don't have to add them manually.
Also every Entity-Struct extending the <code>Entity</code>-Class has the associated Properties + Getters and Setters automatically.</p>
<p>The only Exception are <code>MappingDefinitions</code>, there these Fields aren't added automatically.</p>
<h2>What do you have to do?</h2>
<p>We have extended the <code>dataabstractionlayer:validate</code>-command to check for fields that don't have a mapped Column.
So run this command to check for Definitions that previously didn`t had these fields.
For those entities you have to write Migrations and add these fields.
For every definitions that has those fields you can remove them from the FieldDefinitions and EntityStructs.</p>
<h3>2019-04-29: Changed context injected to payment handler</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>From now on the <code>\Shopware\Core\System\SalesChannel\SalesChannelContext</code> is injected into the methods of
<code>\Shopware\Core\Checkout\Payment\Cart\PaymentHandler\SynchronousPaymentHandlerInterface</code> and
<code>\Shopware\Core\Checkout\Payment\Cart\PaymentHandler\AsynchronousPaymentHandlerInterface</code>.
This should give you more information about the current checkout and saleschannel context,
but it breaks the current interfaces. Please adjust your payment handler accordingly.
Please be also aware that the SalesChannelContext <strong>may</strong> contain certain information. Some of its properties are nullable,
so make sure they are set, before you use them. </p>
<h3>2019-04-29: Added Api-Browser for SalesChannelEntities</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We have added the ApiBrowser functionality of the EntityDefinitions for the SalesChannelApi-Entities as well.
You can find the SwaggerUI under <code>/sales-channel-api/v1/_info/swagger.html</code>.</p>
<p>We also added a configuration, to control whether the ApiBrowser functionality is available or not.
You can control it over the <code>api.api_browser.public</code> entry in your shopware.yaml:</p>
<pre><code class="language-yaml">api:
  api_browser:
    public: true</code></pre>
<h3>2019-04-26: Removed public shorthand functions of context</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The context used to have the shorthand functions <code>getUserId</code> and <code>getSalesChannelId</code>.</p>
<p>The problem is that it depended on the Source of the Context whether these Ids were set or not.
Especially in the case of the <code>salesChannelId</code> this was problematic, because it silently returned the DefaultId in case the source didn't match.</p>
<p>We removed the shorthand functions, so noe you have to take care of checking the ContextSource:</p>
<pre><code class="language-php">if (!$context-&gt;getSource() instanceof AdminApiSource) {
    throw new InvalidContextSourceException(AdminApiSource::class, \get_class($context-&gt;getSource()));
}</code></pre>
<p>We have added the <code>InvalidContextSourceException</code> for the case that a different CntextSource was expected.</p>
<h3>2019-04-26: Public controllers</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>In the past you had to extend the whitelist in the SalesChannelApi-/ApiAuthenticationLister
if you wanted to create a controller which doesn't required a logged in user.
Since it was almost impossible for third party developers to extend this list,
the behavior has changed.
If you now want to create a public route you can do this with an annotation. Example:</p>
<p><code>* @Route("/api/v{version}/_action/user/my-unprotected-route", defaults={"auth_required"=false})</code></p>
<h3>2019-04-26: Currency iso code added</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The currency entity now has an additional field called isoCode which is required and not translated.
This field contains a 3 letter code according to the ISO 4217 standard.</p>
<h3>2019-04-25: Vue Vuex in the Administration</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>Vuex is a Vue.js plugin and library that allows you to share state between components. This is done by using a single object which is accessible by all component via the <code>$store</code> property. Vuex applies a flux pattern on this store which means every change of the store's state must be done via commits (synchronous changes) or actions (asynchronous changes).</p>
<p>Well this is not completely true because we deactivated strict mode (more on this later).</p>
<p>This is not a documentation about Vuex but an overview how we want to use Vuex in our application. If you are not familiar with Vuex I strongly recommend reading the documentation at <a href="vuex.vuejs.org">vuex.vuejs.org</a>.</p>
<h3>Define Own Store Modules</h3>
<p>Store modules should only be registered by the top-level components of complex structures (e.g. your <code>sw-page</code> component or things like the <code>sw-component-tree</code>). Keep in mind that the preferred way to share state in Vue.js still is passing properties to children and emitting events back.</p>
<p>We recommend to create your module in a separate Javascript file in your components folder named <code>state.js</code> that exports your state definition</p>
<pre><code class="language-javascript">// state.js
export default {
   namespaced: true,
   state: { },
   mutations: { ... }
}</code></pre>
<p>To register the module use the <code>registerModule</code> function of the Vuex store in the <code>beforeCreated</code> lifecycle hook of your component. Also don't forget to clean up your state when your component is destroyed.</p>
<p>If you register a module on the store keep in mind that it follows the same rules as if you would create a component. That means that store modules which are created from the same source share a static state. if you need a &quot;clean&quot; store module every time you register it and (in most cases this is exactly what you want) define your state property as a function. see <a href="https://vuex.vuejs.org/guide/modules.html#module-reuse">https://vuex.vuejs.org/guide/modules.html#module-reuse</a> for an explanation</p>
<pre><code class="language-javascript">export default {
  state() {
      return { ... };
  }  </code></pre>
<p>As convention your store module name should be your component's name in camelcase (because you must be able to access the name in object notation).</p>
<pre><code class="language-javascript">import componentNameState from './state.js'

export default {
  name: 'component-name'

  beforeCreated() {
    this.$store.registerModule('componentName', componentNameState);
    }
  beforeDestroye() {
    this.$store.unregisterModule('componentName');
    }</code></pre>
<p>You may note that we don't follow our usual convention to wrap the functionality of the lifecyclehook in an extra method. This is because the registration of your state is mandatory and should not be overwritten by components extending your component.</p>
<h3>Strict mode and Problems with v-model</h3>
<p>Because Vuex does not work well with Vue.js' <code>v-model</code> directive we turned off strict mode. That means that state can be written directly. However, avoid changing the state directly as much as possible because it could cause problems with Vue.js' reactivity. At least first level properties of your module must be commited.</p>
<pre><code class="language-javascript">// state.js
export default {
   state: {
    // product is a first level property
    product {
      // id may be changed directly with full reactivity
      id: ''
    }
   },
   mutations: { ... }
}</code></pre>
<h3>Global State</h3>
<p>Right now we're migration global state to vuex stores. This includes the current language and admin locale as well as notification management and error handling. All global actions and mutations will be documented in the component library eventually.</p>
<p>If you need to create global state on your own you can create an Vuex module in the <code>/src/app/state/</code> folder of the application. Because the Vuex modules must be named we could not apply automatic registration (yet). So You must add your global module manually in <code>/src/app/state/index.js</code> .</p>
<h3>2019-04-25: Naming database constraints</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>With the newest MySQL version <code>CONSTRAINTS</code> must be unique across all tables. This means that</p>
<p><code>CONSTRAINT json.custom_fields CHECK (JSON_VALID(custom_fields))</code> is no longer valid. The new constraint name should be:</p>
<p><code>CONSTRAINT json.table_name.custom_fields CHECK (JSON_VALID(custom_fields))</code>. This is true for all CONSTRAINT, not only JSON_VALID().</p>
<h3>2019-04-25: Consistent locale codes</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>Until now we used two locale code standards.</p>
<p>Bcp-47 inside vue.js (administration) and IEC_15897 inside the Php backend.</p>
<p>Now we use the BCP-47 standard for both. This means, that the locale codes changed from <code>en_GB</code> to <code>en-GB</code>.</p>
<p>Where does this effect you?</p>
<ul>
<li>First you need to reinitialize your Shopware installation after you pulled these changes (./psh.phar init)</li>
<li>composer.json of your plugins</li>
<li>Changelogs of your plugins</li>
<li>Locale Repository</li>
<li>Snippet files of all modules</li>
</ul>
<h3>composer.json</h3>
<p>You have to change the locale codes inside the extra section of your plugin composer.json from:</p>
<pre><code class="language-json">"extra": {
  "shopware-plugin-class": "Swag\\Example",
  "copyright": "(c) by shopware AG",
  "label": {
    "de_DE": "Example Produkte für Shopware",
    "en_GB": "Example Products for Shopware"
  }
},</code></pre>
<p>to:</p>
<pre><code class="language-json">
"extra": {
  "shopware-plugin-class": "Swag\\Example",
  "copyright": "(c) by shopware AG",
  "label": {
    "de-DE": "Example Produkte für Shopware",
    "en-GB": "Example Products for Shopware"
  }
},</code></pre>
<h3>Changelogs</h3>
<p>The <code>en-GB</code> changelog file still is: <code>CHANGELOG.md</code>.</p>
<p>The format for all other locales changed from <code>CHANGELOG-??_??.md</code> to <code>CHANGELOG_??-??.md</code>. For example a german changelog file changed from <code>CHANGELOG-de_DE.md</code> to <code>CHANGELOG_de-DE.md</code>.</p>
<h3>Locale Repository</h3>
<p>If you use the locale repository inside your code, the locale codes will now return in the new format.</p>
<h3>Snippet files of all modules</h3>
<p>We renamed all snippet files, from <code>en_GB.json</code> to <code>en-GB.json</code>.</p>
<p>For consistency, you should do the same in your plugins.</p>
<h3>2019-04-18: Document title using VueMeta</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We just implemented VueMeta 1.6.0 to shopware!</p>
<p>For now, it's only used to configure dynamic document titles in addition to the recently implemented favicons per module and will be added to every, already implemented module. Please be sure to add it to every new module! Additonally: Every Moduels <code>name</code> property has been refactored in the style of its identifier. (If its <code>sw-product-stream</code> the name now is <code>product-stream</code>.)</p>
<p>To provide more detailed information, we added the <code>this.$createTitle()</code> method to get an easily generated document title like <code>Customers | Shopware administration or Awesome Product | Products | Shopware administration</code></p>
<p>Therefore every Module should set a <code>title</code> property with a snippet for its in the modules <code>index.js</code>:</p>
<pre><code class="language-javascript">
Module.register('sw-product', {
    name: 'sw-product.general.mainMenuItemGeneral',
    ...</code></pre>
<p>And also add the <code>metaInfo</code> property on <strong>every pages</strong> <code>index.js</code>:</p>
<pre><code class="language-javascript">
...
metaInfo() {
    return {
        title: this.$createTitle()
    };
},

computed: {
    ...</code></pre>
<p>Alternativly <strong>for every detail page</strong> add an identifier (e.g. using the placeholder mixin to ensure fallback-translations):</p>
<pre><code class="language-javascript">...
mixins: [
    Mixin.getByName('placeholder')
],

metaInfo() {
    return {
        title: this.$createTitle(this.identifier)
    };
},

computed: {
    identifier() {
        return this.placeholder(this.product, 'name');
    },
},
...</code></pre>
<p>The <code>$createTitle(String = null, ...String)</code> method uses the current page component to read its module title. The first parameter should be used in detail pages to also display its identifier like the product name or a full customer name to add it to the title. Every following parameter is fully optional and not in use yet, but if used would be added to the title in the same fashion.</p>
<h3>2019-04-18: Storefront page ajax</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>To secure the Storefront we made every Controller-Action inside the StorefrontBundle not requestable via XmlHttpRequests/AJAX.</p>
<p>You can override this by allowing XmlHttpRequests in the Route Annotation</p>
<p>with the <code>defaults={"XmlHttpRequest"=true}</code> Option.</p>
<p>Example:</p>
<pre><code class="language-php">/**
@Route("/widgets/listing/list/{categoryId}", name="widgets_listing_list", methods={"GET"}, defaults={"XmlHttpRequest"=true})
*/
public function listAction(Request $request, SalesChannelContext $context): JsonResponse</code></pre>
<p>For more Examples take a look inside the PageletControllers.</p>
<h3>2019-04-18: SalesChannel Entity definition</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We impemented a central way to define entities which should be available over the <code>sales-channel-api</code>.</p>
<p>An EntityDefinition can now define a decoration definition for the <code>sales-channel-api</code>:</p>
<pre><code class="language-php">&lt;?php
// ...
class ProductDefinition extends EntityDefinition
{
    public static function getEntityName(): string
    {
        return 'product';
    }

    public static function getSalesChannelDecorationDefinition()
    {
        return SalesChannelProductDefinition::class;
    }
}</code></pre>
<h3>Declaring the SalesChannelDefinition</h3>
<p>A decorating sales channel definition for an entity should extend the original definition class.</p>
<pre><code class="language-php">&lt;?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\SalesChannel;

class SalesChannelProductDefinition 
    extends ProductDefinition 
    implements SalesChannelDefinitionInterface
{
    use SalesChannelDefinitionTrait;
}</code></pre>
<p>These declaration allows to replace different functionalities for an entity:</p>
<ul>
<li>Rewriting the storage - getEntityName()
<ul>
<li>Example usage: I want to denormalize my entities in a different table for better performance</li>
</ul></li>
<li>Rewriting the DTO classes - getEntityClass getEntityCollection
<ul>
<li>Example usage: I want to provide some helper functions or more properties in the storefront</li>
</ul></li>
<li>Adding or removing some fields - defineFields
<ul>
<li>Example usage: I can add more fields for an entity which will be displayed in a storefront or in other clients</li>
</ul></li>
</ul>
<h3>Association Handling</h3>
<p>It is import to override the defineFields function to rewrite association fields with their sales channel decoration definition:</p>
<pre><code class="language-php">protected static function defineFields(): FieldCollection
{
    $fields = parent::defineFields();

    self::decorateDefinitions($fields);

    return $fields;
}</code></pre>
<p>This decoration call replaces all entity definition classes of the defined association fields with the decorated definition.</p>
<h3>Basic filters</h3>
<p>Additionally by implementing the <code>\Shopware\Core\System\SalesChannel\Entity\SalesChannelDefinitionInterface</code> interface the developer has the opportunity to add some basic filters if the entity will be fetched for a sales channel:</p>
<pre><code class="language-php">public static function processCriteria(
    Criteria $criteria, 
    SalesChannelContext $context
) : void {
    $criteria-&gt;addFilter(
        new EqualsFilter('product.active', true)
    );
}</code></pre>
<h3>Reigster the definition</h3>
<p>Like the EntityDefinition classes the Definition classes for sales channel entities has to be registered over the Dependency Injection Container by tagging the definition with the <code>shopware.sales_channel.entity.definition</code> tag.</p>
<pre><code class="language-xml">&lt;service 
        id="Shopware\Core\Content\Product\SalesChannel\SalesChannelProductDefinition"&gt;

&lt;tag name="shopware.sales_channel.entity.definition" entity="product"/&gt;

&lt;/service&gt;</code></pre>
<h3>Repository registration</h3>
<p>Like the entity repository for the DAL, the each registered sales channel entity definition gets an own registered repository. The repository is registered like the original entity definition but with an additional <code>sales_channel.</code> prefix:</p>
<p>Example: <code>sales_channel.product.repository</code></p>
<p>The registered class is an instance of <code>\Shopware\Core\System\SalesChannel\Entity\SalesChannelRepository</code>.</p>
<p>This repository provides only a read functions:</p>
<pre><code class="language-php">public function search(
    Criteria $criteria, 
    SalesChannelContext $context
) : EntitySearchResult

public function aggregate(
    Criteria $criteria, 
    SalesChannelContext $context
) : AggregatorResult

public function searchIds(
    Criteria $criteria, 
    SalesChannelContext $context
) : IdSearchResult</code></pre>
<h3>Api Routes</h3>
<p>All registered sales channel definitions has registered api routes: (example for product)</p>
<pre><code class="language-php">sales-channel-api.product.detail
    /sales-channel-api/v{version}/product/{id}

sales-channel-api.product.search-ids
    /sales-channel-api/v{version}/search-ids/product

sales-channel-api.product.search
    /sales-channel-api/v{version}/product</code></pre>
<h3>Event Registration</h3>
<p>Entities which loaded for a sales channel has own events. All Events of the Data Abstraction Layer are prefixed with sales_channel. (Example for product):</p>
<ul>
<li><code>sales_channel.product.loaded</code></li>
<li><code>sales_channel.search.result.loaded</code></li>
<li><code>sales_channel.product.id.search.result.loaded</code></li>
<li><code>sales_channel.product.aggregation.result.loaded</code></li>
</ul>
<h3>2019-04-17: Refactored rules match function</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The Rules were refactored, so that the match function not longer returns a reason object which contains the debug messages. Instead the match function directly returns a bool if the rule is matching or not.</p>
<h3>2019-04-17: Internal request removed</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>The <code>InternalRequest</code> class alternative to the Symfony Request has been removed as it is not required anymore.</p>
<p>To check required parameters etc. use the Symfony Request or even better the <code>RequestDataBag</code> or <code>QueryDataBag</code> and validate your input using the <code>DataValidator</code>. You can see some examples in the <code>AccountService</code>.</p>
<h3>2019-04-17: Administration open API Housekeeping</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We are currently working on a lot of housekeeping tasks to make the administration code base as clean as possible. It should be easier for new developers to spot best practices inside the code. This is why we have to adjust some general things like events.</p>
<p><em>This changes are not merged yet! We will write more update logs when some of the mentioned topics are inside the master branch.</em></p>
<p>Here are the most important changes:</p>
<h3>Custom Vue events</h3>
<ul>
<li>All custom Vue events will be kebab-case.</li>
<li>This is also a Vue.js best practice: <a href="https://vuejs.org/v2/guide/components-custom-events.html#Event-Names">https://vuejs.org/v2/guide/components-custom-events.html#Event-Names</a> camelCase and snake_case are not allowed.</li>
<li>We don't put the whole component name inside the event name any longer. The event can only be used on the component itself in most cases. So there should be no duplicate issues whatsoever. For more complex &quot;flows&quot; you can add names like &quot;folder-item&quot; or &quot;selection&quot; inside your event name.</li>
<li>The event name itself should follow this order: object -&gt; prefix -&gt; action</li>
</ul>
<p>For example:</p>
<pre><code class="language-javascript">// product (object)
// before (prefix)
// load (action)

this.$emit('product-before-load');</code></pre>
<p>Object and prefix are only needed in more complex scenarios when more events are involved in one context. E.g. events for &quot;folders&quot; and &quot;products&quot; being called on a single component. When you just want to trigger a single save action on one small component a simple 'save' as an event name should be fine.</p>
<p>More examples:</p>
<pre><code class="language-javascript">// Bad
this.$emit('itemSelect'); // No camel case
this.$emit('item_select'); // No snake case
this.$emit('item--select'); // No double dash
this.$emit('sw-component-item-select'); // No component names
this.$emit('select-item'); // Object always before action

// Good
this.$emit('item-select');

/* ----------------------- */

// Bad
this.$emit('folder-saving');
this.$emit('column-sorting');

// Good
this.$emit('folder-save');
this.$emit('folder-sort');

/* ----------------------- */

// Bad
this.$emit('customer-saved'); // No past tense

// Good
this.$emit('customer-save')
this.$emit('customer-finish-save'); // Or use success prefix instead

/* ----------------------- */

// Bad
this.$emit('on-save'); // No filler or stateul words like "on" or "is"

// Good
this.$emit('save')</code></pre>
<h3>SCSS Variables</h3>
<p>We will remove the scoped / re-mapped color variables from all components. <strong>From now on you can use the color variables directly.</strong> Component specific variables should only be used when you really have multiple usages of a value e.g. &quot;$sw-card-base-width: 800px&quot;.</p>
<p>We decided to remove this pattern because plugin developers are not able to override those variables anyway. For us internally the benefit isn't that great because we are not changing component colors all the time.</p>
<h3>SCSS Code style</h3>
<p>Improve and fix some more code style rules:</p>
<ul>
<li>Only use spaces, not tabs</li>
<li>Always indent 4 spaces</li>
<li>No !important when possible</li>
<li>No camelCase or snake_case in selectors</li>
</ul>
<h3>JS/Vue.js Code style and housekeeping</h3>
<ul>
<li>Empty lines after methods and props</li>
<li>No methods with complex logic inside &quot;data&quot;</li>
<li>Remove unused props</li>
<li>Remove default value for required props</li>
<li>Check usage of methods for lifecycle hooks (createdComponent etc.)</li>
</ul>
<h3>2019-04-16: Configuration files</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>For the extraction of a production-ready community edition template, some configuration variables will be moved into the platform.</p>
<p>The routing configuration has already been moved into the bundles of the platform and they will be registered automatically. You can find the configuration file in the same folder structure like our plugins: <code>Resources/config/route.xml</code></p>
<p>More configuration files will most likely follow in the near future.</p>
<h3>2019-04-16: Composer dependencies</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>To ensure every bundle inside our mono-repository can be used standalone, their dependencies in the bundles <code>composer.json</code> must be maintained. Therefore we no longer update the platform's <code>composer.json</code> manually, except for metadata updates.</p>
<p>There is a new script ran as pre-commit hook, which collects every dependency of the bundles and merges them into the platforms <code>composer.json</code>. If the script notices any difference, you'll get a warning and have to review the changes:</p>
<blockquote>
<p>ERROR! The platform composer.json file has changed. Please review your commit and add the changes.</p>
</blockquote>
<h3>2019-04-15: Storefront-API is now SalesChannel-API</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>For plausibility reasons we removed the Storefront term from the core bundles and named it SalesChannel. The idea beeing:</p>
<p>The Core knows about sales channels and exposes an API for SalesChannels. All customer facing applications then connect to this SalesChannel-API.Doesn't matter whether its a fully featured store front, a buy button, something with voice or whatever.</p>
<p>Therefore:</p>
<ul>
<li>All former storefront-controllers now reside in a <code>SalesChannel</code> Namespace as <code>SalesChannel</code> controllers</li>
<li>The api is now under <code>.../sales-channel-api/...</code></li>
<li>A test exists to secure this</li>
</ul>
<h3>2019-04-11: Roadmap update</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>Here you will find a current overview of the epics that are currently being implemented, which have been completed and which will be implemented next.</p>

<p><strong>Open</strong><br />
Work on these Epics has not yet begun.</p>

<ul>
	<li>Sales Channel</li>
</ul>

<p><strong>Next</strong><br />
These epics are planned as the very next one</p>

<ul>
    <li>CLI and Web Installer</li>
</ul>

<p><strong>In Progress</strong><br />
These Epics are in the implementation phase</p>

<ul>
    <li>Theme System</li>
    <li>SEO Basics</li>
	<li>Products</li>
	<li>Core Settings</li>
	<li>Promotions</li>
	<li>Plugin Manager</li>
	<li>Variants / Properties</li>
	<li>Shipping / Payment</li>
	<li>Import / Export</li>
	<li>Mail Templates</li>
	<li>Storefront API / Page, Pagelets</li>
	<li>CMS</li>
	<li>Categories / Navigation</li>
	
</ul>

<p><strong>Review</strong><br />
All Epics listed here are in the final implementation phase and will be reviewed again.</p>

<ul>
	<li>Backend Search</li>
	<li>Rule Builder</li>
	<li>Plugin System</li>
    <li>Product Streams</li>
    <li>Newsletter Integeration</li>
	<li>Custom Fields</li>
	<li>Documents</li>
	<li>User</li>
</ul>

<p><strong>Done</strong><br />
These epics are finished</p>

<ul>
    <li>Tags</li>
    <li>Customer</li>
    <li>Number Ranges</li>
    <li>User Profile</li>
    <li>Snippets</li>
	<li>Media Manager</li>
    <li>Order</li>
	<li>Content Translations</li>
	<li>Supplier</li>
	<li>Background processes</li>
</ul>

<h3>2019-04-09: Product table renaming</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We renamed the following tables as follow:</p>
<ul>
<li>product.variatios =&gt; product.options
<ul>
<li>Relation between variants and their options which used for the generation.
*product.configurators =&gt; product.configuratorSettings</li>
<li>Relation between products and the configurator settings. This table are used for the administration configurator wizard</li>
</ul></li>
<li>product.datasheet =&gt; product.properties
<ul>
<li>Relation between products and their property options. This options are not related to physical variants with own order numbers</li>
</ul></li>
<li>configuration_group =&gt; property_group
<ul>
<li>Defines a group for possible options like color, size, ...</li>
</ul></li>
<li>configuration_group_option =&gt; property_group_option</li>
</ul>
<p>All related api routes and associations are renamed too:</p>
<ul>
<li>/api/v1/property-group</li>
<li>/api/v1/property-group-option</li>
<li>/api/v1/product/{id}/options</li>
<li>...</li>
</ul>
<p>Detail changes can be found here: <a href="https://github.com/shopware/platform/commit/1d8af890792df21bed13ef94afa1ac684d6d7f7d">https://github.com/shopware/platform/commit/1d8af890792df21bed13ef94afa1ac684d6d7f7d</a></p>
<h3>2019-04-09: Plugin structure refactoring</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We made a refactoring of the plugin structure which affect ALL plugins!</p>
<ul>
<li>The &quot;type&quot; in the composer.json must now be <code>shopware-platform-plugin</code>. This is necessary to differentiate between Shopware 5 and Shopware platform plugins</li>
<li>You now have to provide the whole FQN of your plugin base class in the composer.json. Add something like this to the &quot;extra&quot; part of the composer.json: <code>"shopware-plugin-class": "SwagTest\\SwagTest"</code>, The old identifier <code>installer-name</code> is no longer used</li>
<li>You now have to provide valid autoload information about your plugin with the composer.json:</li>
</ul>
<pre><code class="language-json">"autoload": {
    "psr-4": {
        "SwagTest\\": ""
    }
}</code></pre>
<p>This give also the opportunity to do something like this:</p>
<pre><code class="language-json">"autoload": {
    "psr-4": {
        "Swag\\Test\\": "src/"
    }
}</code></pre>
<p>Which should really tidy up the root directory of a plugin</p>
<ul>
<li>If you want to provide a plugin icon, you have to specify the path of the icon relative to your plugin base class in the composer.json. Add a new field to the &quot;extra&quot; part of the composer.json: <code>"plugin-icon": "Resources/public/plugin.png",</code></li>
<li>We introduced a default path for the plugin config file. It points to <code>Resources/config/config.xml</code> relative from your plugin base class. So if you put your config there, Shopware will automatically generated a configuration form for your plugin. If you want another path, just overwrite the <code>\Shopware\Core\Framework\Bundle::getConfigPath</code> method</li>
<li>We introduced some more defaults path which could all be changed by overwriting the appropriate method. The &quot;Resources&quot; directory is always relative to the base class of your plugin
<ul>
<li><code>Resources/config/services.xml</code> path to your default services.xml to register your custom services</li>
<li><code>[Resources/views]</code> Array of views directorys of your plugin</li>
<li><code>Resources/adminstration</code> the location of your administration files and entry point of extensions of the administration</li>
<li><code>Resources/storefront</code> same for the storefront</li>
<li><code>Resources/config/</code> directory which will be used to look for route config files</li>
</ul></li>
</ul>
<p>All in all, the composer.json should contain descriptive information and the plugin base class the runtime configuration</p>
<h3>2019-04-08: Unique default ids</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We made all IDs defined in <code>Shopware\Core\Defaults.php</code> unique, so the Ids changed.</p>
<p>If you experience some problems with logging in to the Admin after rebasing your branch please check the localStorage for the key sw-admin-current-language and delete this key.</p>
<p>After that it should work as before.</p>
<h3>2019-04-08: Jest as testing framework (admin)</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We made the shift from Karma (including Chai, Jasmine and Sinon) to Jest as our primary JavaScript testing framework. Jest provides us with a lot of functionality:</p>
<ul>
<li>Snapshot testing using the offical @vue/test-utils tool</li>
<li>Mocks for ES6 classes, Timers including automatic mocking &amp; clearing</li>
<li>Spies are part of Jest, we don't need a separate framework anymore</li>
<li>Running tests in parallel</li>
<li>Debugging Support using Chrome Inspect</li>
<li>Code Coverage report using Istanbul with a Clover Report + inline in the terminal</li>
</ul>
<p>Documentation links:</p>
<ul>
<li>Matchers / expect: <a href="https://jestjs.io/docs/en/expect">https://jestjs.io/docs/en/expect</a></li>
<li>Vue Test Utils: <a href="https://vue-test-utils.vuejs.org/guides/">https://vue-test-utils.vuejs.org/guides/</a></li>
</ul>
<p>The existing tests have been converted to Jest' Matcher API using <a href="https://github.com/skovhus/jest-codemods">https://github.com/skovhus/jest-codemods</a></p>
<p>The test specs can be found in <code>Administration/Resources/administration/test</code></p>
<h3>Running tests</h3>
<pre><code class="language-bash">./psh.phar administration:unit
./psh.phar administration:unit-watch # Watch mode</code></pre>
<h3>Snapshot Testing</h3>
<p><img src="https://jestjs.io/img/content/failedSnapshotTest.png" alt="snapshot testing" /></p>
<p>Snapshot tests are a very useful tool whenever you want to make sure your UI does not change unexpectedly. It's supported using @vue/test-utils:</p>
<pre><code class="language-javascript">import { shallowMount } from '@vue/test-utils';
import swAlert from 'src/app/component/base/sw-alert';

it('should render correctly', () =&gt; {
    const title = 'Alert title';
    const message = '&lt;p&gt;Alert message&lt;/p&gt;';

    const wrapper = shallowMount(swAlert, {
        stubs: ['sw-icon'],
        props: {
            title
        },
        slots: {
            default: message
        }
    });
    expect(wrapper.element).toMatchSnapshot();
});</code></pre>
<p>Snapshots are specialized files from Jest which are representing the actual DOM structure. If a refactoring changes the DOM structure unintentionally, the test will fail. The developer can either update the snapshot to reflect the new DOM structure when the structure change was intended or fix the structure until the test passes again.</p>
<h3>Shallow Mounting</h3>
<p>Please consider prefering shallowMount instead of mount. Shallow mounting a component lets you stub additional components, fill slots, set props etc. Here's the documentation: <a href="https://vue-test-utils.vuejs.org/api/#shallowmount">https://vue-test-utils.vuejs.org/api/#shallowmount</a></p>
<h3>Vue Router Support</h3>
<p>If your compomnent is using router-link or router-view, you can simply stub them:</p>
<pre><code class="language-javascript">import { shallowMount } from '@vue/test-utils'

shallowMount(Component, {
    stubs: ['router-link', 'router-view']
});</code></pre>
<h4>INSTALLING VUE ROUTER FOR A TEST</h4>
<pre><code class="language-javascript">import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'

const localVue = createLocalVue()
localVue.use(VueRouter)

shallowMount(Component, {
    localVue
});</code></pre>
<h4>MOCKING $ROUTE</h4>
<pre><code class="language-javascript">import { shallowMount } from '@vue/test-utils'

const $route = {
    path: '/some/path'
};

const wrapper = shallowMount(Component, {
    mocks: {
      $route
    }
});

console.log(wrapper.vm.$route.path);</code></pre>
<h4>Triggering events</h4>
<pre><code class="language-javascript">const wrapper = shallowMount(Component);

wrapper.trigger('click');

// With options
wrapper.trigger('click', { button: 0 })</code></pre>
<h3>2019-04-08: Association loading</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>With the latest DAL change, you are no longer able to auto-load <code>toMany</code> associations as they have a huge performance impact. From now on, please enrich your criteria object by adding associations like:</p>
<pre><code class="language-php">$criteria-&gt;addAssociation('comments');</code></pre>
<p><strong>Please think about when to load toMany associations and if they are really necessary there.</strong></p>
<p>Every <code>toOne</code> association will be fetched automatically unless you've disabled it. Some fields like the <code>ParentAssociationField</code> are disabled by default because they may lead to a circular read operation.</p>
<h3>AssociationInterface</h3>
<p>The <code>AssociationInterface</code> has been removed in favor of the abstract <code>AssociationField</code> class because there were some useless type-hints in the code and it just feels right now.</p>
<h3>2019-04-05: Favicons for each module</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>It is now possible to define a favicon for each module in the administration. The favicon, which is just a .png version of the module-icon, is switched dynamically depending on what module is active at the moment. Currently there are 7 favicons that are located in <code>administration/static/img/favicon/modules/</code>.</p>
<p>When no favicon is defined for the module the default shopware signet is used as a fallback.</p>
<p>The favicon can be defined in the module registration.</p>
<pre><code class="language-javascript">Module.register('sw-category', {
    name: 'Categories',
    icon: 'default-package-closed',
    favicon: 'icon-module-products.png'
});</code></pre>
<h3>2019-04-05: Renamed CheckoutContext to SalesChannelContext</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>
<p>We renamed the <code>CheckoutContext</code> to <code>SalesChannelContext</code> and moved <code>Checkout\Context</code> to `System\SalesChannelConte</p>
<p>In perspective it is planned to</p>
<ul>
<li>move all SalesChannel related classes from <code>Framework</code> to <code>System\SalesChannel</code></li>
<li>Rename the StoreFront files in the Core to SalesChannel</li>
<li>Rename the API-Routes</li>
<li>but keep the Controllers / Services / Repositories in the corresponding domain modules</li>
</ul>
<p>As always: <strong>sorry for the inconvenience!</strong></p>
<h3>2019-04-04: Refactored viewData (Breaking change)</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We have completely removed the<strong> Entity::viewData </strong>property.</p>

<p><strong>Why was it removed?</strong></p>

<ul>
	<li>ViewData was always available in the json response under meta. However, this led to deduplication becoming obsolete.</li>
	<li>It had a massive impact on the hydrator performance</li>
</ul>

<p><br />
<strong>What was viewData needed for?</strong></p>

<ul>
	<li>Generally this was needed for translatable fields. The name from the language inheritance was available under viewData.name.</li>
	<li>Furthermore, this was also used for the parent-inheritance (currently used only for products). If a varaint has no own assigned manufacturer, the manufacturer of the parent should be available. Under viewData.manufacturer therefore the manufacturer of the father product was available</li>
</ul>

<p><strong>How do I get this information now?</strong></p>

<ul>
	<li>Translate fields are now available under the translated. The values listed there were determined using the language inheritance.</li>
	<li>The context object contains a switch &quot;considerInheritance&quot;. This can be sent via api as header (sw-inheritance) to consider the inheritance in search and read requests.</li>
</ul>

<p>This value is initialized for the following routes as follows</p>

<p><strong>/api </strong>Default <strong>false</strong><br />
<strong>/sales-channel-api </strong>Default <strong>true</strong><br />
<strong>twig-frontend</strong> Default <strong>true</strong></p>

<h3>2019-04-03: Rename product_price_rule to product_price</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We changed the naming of product_price_rule table and all corresponding php classes, api routes, php properties.</p>

<p>The new name of the table is <strong>product_price</strong>.</p>

<p>This is reflected in the corresponding api routes:</p>

<ul>
	<li>/api/v1/product-price</li>
	<li>/api/v1/product/{id}/prices</li>
</ul>

<h3>2019-04-03: LESS has been removed</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<ul>
	<li>LESS has been removed from the administration source code</li>
	<li>The duplicated LESS base variables are no longer available. Components LESS which uses base variables will not be functional.</li>
	<li>Please do not use LESS inside core components any longer because it is also no longer supported by the component library.</li>
	<li>However the package.json dependency has not been completely removed. External plugins should still have the posibility to use LESS. But we will recommend SCSS in our documentation.</li>
	<li>Some documentation markdown files may still include LESS examples. Those will be edited by the documentation squad soon.</li>
</ul>

<h3>2019-04-03: Admin scaffolding components</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>With the new data handling, we implemented a list of scaffolding components to prevent boiler plate code and keep data handling as simple as possible.</p>

<p>The following components are now available:</p>

<ul>
	<li>sw-entity-listing</li>
	<li>sw-entity-multi-select</li>
	<li>sw-entity-single-select</li>
	<li>sw-one-to-many-grid</li>
</ul>

<p>This components are related to different use cases in an administration module.</p>

<p><strong>sw-entity-listing</strong><br />
A decoration of the <strong>sw-data-grid </strong>which can be used as primary listing component of a module. All functions of the <strong>sw-data-grid</strong> are supported.</p>

<p>Additionally configuration for the <strong>sw-entity-listing </strong>component are:</p>

<ul>
	<li><strong>`repository [required] - Repository</strong>

	<ul>
		<li>Provide the source repository where the data can be loaded. All operations are handled by the component itself. Pagination, sorting, row editing are supported and handled out of the box.</li>
	</ul>
	</li>
	<li><strong>items [required] - SearchResult</strong>
	<ul>
		<li>The first result set must be provided in order to avoid unnecessary server request when the initial load must contain certain logics.</li>
	</ul>
	</li>
	<li><strong>detailRoute [optional| - String</strong>
	<ul>
		<li>allows to define a route for a detail page. If set the grid creates a edit action to open the detail page</li>
	</ul>
	</li>
</ul>

<pre>
import { Component } from &#39;src/core/shopware&#39;;
import Criteria from &#39;src/core/data-new/criteria.data&#39;;
import template from &#39;./sw-show-case-list.html.twig&#39;;

Component.register(&#39;sw-show-case-list&#39;, {
    template,
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    data() {
        return {
            repository: null,
            products: null
        };
    },
    
    computed: {
        columns() {
            return this.getColumns();
        }
    },
    
    created() {
        this.createdComponent();
    },
    
    methods: {
        createdComponent() {
            this.repository = this.repositoryFactory
                .create(&#39;product&#39;, &#39;/product&#39;);
        
            return this.repository
                .search(new Criteria(), this.context)
                .then((result) =&gt; {
                    this.products = result;
                });
        },
        
        getColumns() {
            return [{
                property: &#39;name&#39;,
                dataIndex: &#39;name&#39;,
                label: this.$tc(&#39;sw-product.list.columnName&#39;),
                routerLink: &#39;sw.show.case.detail&#39;,
                inlineEdit: &#39;string&#39;,
                allowResize: true,
                primary: true
            }];
        }
    }
});

&lt;sw-page&gt;
    &lt;template slot=&quot;content&quot;&gt;
    
    &lt;sw-entity-listing v-if=&quot;products&quot;
                        :items=&quot;products&quot;
                        :repository=&quot;repository&quot;
                        :columns=&quot;columns&quot;
                        detailRoute=&quot;sw.show.case.detail&quot; /&gt;
    
    &lt;/template&gt;
&lt;/sw-page&gt;</pre>

<p><br />
<strong>sw-one-to-many-grid</strong><br />
A decoration of the sw-data-grid which can be used (As the name suggested) to display OneToMany association in a detail page. All functions of the sw-data-grid are supported.</p>

<p>Additionally configuration for the sw-one-to-many-grid component are:</p>

<ul>
	<li><strong>collection [required] - EntityCollection</strong>

	<ul>
		<li>Provide the association collection for this grid. The grid uses it to detect the entity schema and the api route where the data can be loaded or processed.</li>
	</ul>
	</li>
	<li><strong>localMode [optional] - Boolean - default false</strong>
	<ul>
		<li>If set to false, the grid creates a repository (based on the collection data) and sends all changes directly to the server.</li>
		<li>If set to true, the grid works only with the provided collection. Changes (delete, update, create) are not send to the server directly - they will be only applied to the provided collection. Changes will be saved with the parent record.</li>
	</ul>
	</li>
</ul>

<pre>
&lt;sw-one-to-many-grid slot=&quot;grid&quot;
                    :collection=&quot;product.prices&quot;
                    :localMode=&quot;record.isNew()&quot;
                    :columns=&quot;priceColumns&quot;&gt;

&lt;/sw-one-to-many-grid&gt;</pre>

<p><br />
<strong>sw-entity-single-select</strong><br />
A decoration of<strong> sw-single-select</strong>. This component is mostly used for ManyToOne association where the related entity can be linked but not be modified (like product.manufacturer, product.tax, address.country, ...). All functions of the <strong>sw-single-select </strong>are supported.</p>

<p>Additionally configuration for the <strong>sw-entity-single-select</strong> component:</p>

<ul>
	<li><strong>entity [required] - String</strong>

	<ul>
		<li>Provide the entity name like <strong>product</strong>, <strong>product_manufacturer</strong>. The component creates a repository for this entity to display the available selection.</li>
	</ul>
	</li>
	<li>&nbsp;</li>
</ul>

<pre>
&lt;sw-entity-single-select 
    label=&quot;Entity single select&quot; 
    v-model=&quot;product.manufacturerId&quot; 
    entity=&quot;product_manufacturer&quot;&gt;
&lt;/sw-entity-single-select&gt;</pre>

<p><br />
<strong>sw-entity-multi-select</strong><br />
A decoration of <strong>sw-multi-select.</strong> This component is mostly used for ManyToMany asociation where the related entity can be linked multiple times but not be modified (like product.categories, customer.tags, ...).</p>

<p>All functions of the <strong>sw-multi-select </strong>are supported.</p>

<p>Additionally configuration for the <strong>sw-entity-multi-select </strong>component:</p>

<ul>
	<li><strong>collection [required] - EntityCollection</strong>

	<ul>
		<li>Provide an entity collection of an association (Normally used for ManyToMany association). The component creates a repository based on the collection api source and entity schema. All CRUD operations are handled inside the component and can easly be overridden in case of handling the request by yourself.</li>
	</ul>
	</li>
</ul>

<pre>
&lt;sw-entity-multi-select 
    label=&quot;Entity multi select for product categories&quot; 
    :collection=&quot;product.categories&quot;&gt;
&lt;/sw-entity-multi-select&gt;</pre>

<ul>
</ul>

<h3>2019-04-02: New admin data handling</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>The new data handling was created to remove the active record pattern in the admininstration. It uses a repository pattern which is strongly based on the DAL from the PHP part.</p>

<p><strong>Relevant classes</strong></p>

<ul>
	<li>Repository
	<ul>
		<li>Allows to send requests to the server - used for all CRUD operations</li>
	</ul>
	</li>
	<li>Entity
	<ul>
		<li>Object for a single storage record</li>
	</ul>
	</li>
	<li>Entity Collection
	<ul>
		<li>Enable object-oriented access to a collection of entities</li>
	</ul>
	</li>
	<li>Search Result
	<ul>
		<li>Contains all information available through a search request</li>
	</ul>
	</li>
	<li>RepositoryFactory
	<ul>
		<li>Allows to create a repository for an entity</li>
	</ul>
	</li>
	<li>Context
	<ul>
		<li>Contains the global state of administration (Language, Version, Auth, ...)</li>
	</ul>
	</li>
	<li>Criteria
	<ul>
		<li>Contains all information for a search request (filter, sorting, pagination, ...)</li>
	</ul>
	</li>
</ul>

<p><strong>Get access to a repository</strong><br />
To create a repository it is required to inject the RepositoryFactory:</p>

<pre>
Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;],
    
    created() {
        this.repository = this.repositoryFactory.create(&#39;product&#39;);
    }
});</pre>

<p><br />
<strong>How to fetch listings</strong><br />
To fetch data from the server, the repository has a <strong>search</strong> function. Each repository function requires the admin context. This can be injected like the repository factory:</p>

<pre>
Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        // create a repository for the `product` entity
        this.repository = this.repositoryFactory.create(&#39;product&#39;);
    
        this.repository
            .search(new Criteria(), this.context)
            .then((result) =&gt; {
                this.result = result;
            });
    }
});</pre>

<p><br />
<strong>Working with the criteria class</strong><br />
The new admin criteria class contains all functionalities of the php criteria class.</p>

<pre>
Component.register(&#39;sw-show-case-list&#39;, {
    created() {
        const criteria = new Criteria();
        criteria.setPage(1);
    
        criteria.setLimit(10);
    
        criteria.setTerm(&#39;foo&#39;);
    
        criteria.setIds([&#39;some-id&#39;, &#39;some-id&#39;]);
    
        criteria.setTotalCountMode(2);
    
        criteria.addFilter(
            Criteria.equals(&#39;product.active&#39;, true)
        );
    
        criteria.addSorting(
            Criteria.sort(&#39;product.name&#39;, &#39;DESC&#39;)
        );
    
        criteria.addAggregation(
            Criteria.avg(&#39;average_price&#39;, &#39;product.price&#39;)
        );
    
        const categoryCriteria = new Criteria();
        categoryCriteria.addSorting(
            Criteria.sort(&#39;category.name&#39;, &#39;ASC&#39;)
        );
    
        criteria.addAssociation(&#39;product.categories&#39;, categoryCriteria);
    }
});</pre>

<p><br />
<strong>How to fetch a single entity</strong></p>

<pre>
<strong>
</strong>Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.repository = this.repositoryFactory.create(&#39;product&#39;);
    
        const id = &#39;a-random-uuid&#39;;
    
        this.repository
            .get(entityId, this.context)
            .then((entity) =&gt; {
                this.entity = entity;
            });
    }
});
Update an entity
Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.repository = this.repositoryFactory.create(&#39;product&#39;);
    
        const id = &#39;a-random-uuid&#39;;
    
        this.repository
            .get(entityId, this.context)
            .then((entity) =&gt; {
                this.entity = entity;
            });
    },
    
    // a function which is called over the ui
    updateTrigger() {
        this.entity.name = &#39;updated&#39;;
    
        // sends the request immediately
        this.repository
            .save(this.entity, this.context)
            .then(() =&gt; {
    
                // the entity is stateless, the new data has be fetched from the server, if required
                this.repository
                    .get(entityId, this.context)
                    .then((entity) =&gt; {
                    this.entity = entity;
                });
            });
    }
});</pre>

<p><br />
<strong>Delete an entity</strong></p>

<pre>
<strong>
</strong>Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.repository = this.repositoryFactory.create(&#39;product&#39;);
    
        this.repository.delete(&#39;a-random-uuid&#39;, this.context);
    }
});
Create an entity
Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.repository = this.repositoryFactory.create(&#39;product&#39;);
    
        this.entity = this.productRepository.create(this.context);
    
        this.entity.name = &#39;test&#39;;
    
        this.repository.save(this.entity, this.context);
    }
});</pre>

<p><br />
<strong>Working with associations</strong><br />
Each association can be accessed via normal property access:</p>

<pre>
Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.repository = this.repositoryFactory.create(&#39;product&#39;);
    
        const id = &#39;a-random-uuid&#39;;
    
        this.repository
            .get(entityId, this.context)
            .then((product) =&gt; {
                this.product = product;
    
                // ManyToOne: contains an entity class with the manufacturer data
                
                console.log(this.product.manufacturer);
    
    
                // ManyToMany: contains an entity collection with all categories.
                // contains a source property with an api route to reload this data (/product/{id}/categories)
                
                console.log(this.product.categories);
    
    
                // OneToMany: contains an entity collection with all prices
                // contains a source property with an api route to reload this data (/product/{id}/priceRules)
                
                console.log(this.product.priceRules);
            });
    }
});</pre>

<p><br />
<strong>Set a ManyToOne</strong></p>

<pre>
<strong>
</strong>Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.manufacturerRepository = this.repositoryFactory.create(&#39;manufacturer&#39;);
    
        this.manufacturerRepository
            .get(&#39;some-id&#39;, this.context)
            .then((manufacturer) =&gt; {
    
                // product is already loaded in this case
                this.product.manufacturer = manufacturer;
    
                // only updates the foreign key for the manufacturer relation
                
                this.productRepository
                    .save(this.product, this.context);
            });
    }
});</pre>

<p><br />
<strong>Working with lazy loaded associations</strong><br />
In most cases, ToMany assocations are loaded over an additionally request. Like the product prices are fetched when the prices tab will be activated.</p>

<p><strong>Working with OneToMany associations</strong></p>

<pre>
Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.productRepository = this.repositoryFactory.create(&#39;product&#39;);
    
        this.productRepository
            .get(&#39;some-id&#39;, this.context)
            .then((product) =&gt; {
                this.product = product;
    
                this.priceRepository = this.repositoryFactory.create(
                    // `product_price`
                    this.product.prices.entity,
                    
                    // `product/some-id/priceRules`
                    this.product.prices.source
                );
            });
    },
    
    loadPrices() {
        this.priceRepository
            .search(new Criteria(), this.context)
            .then((prices) =&gt; {
                this.prices = prices;
            });
    },
    
    addPrice() {
        const newPrice = this.priceRepository.create(this.context);
    
        newPrice.quantityStart = 1;
        // update some other fields
    
        this.priceRepository
            .save(newPrice, this.context)
            .then(this.loadPrices);
    },
    
    deletePrice(priceId) {
        this.priceRepository
            .delete(priceId, this.context)
            .then(this.loadPrices);
    },
    
    updatePrice(price) {
        this.priceRepository
            .save(price, this.context)
            .then(this.loadPrices);
    }
});</pre>

<p><br />
<strong>Working with ManyToMany associations</strong></p>

<pre>
<strong>
</strong>Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.productRepository = this.repositoryFactory.create(&#39;product&#39;);
    
        this.productRepository
            .get(&#39;some-id&#39;, this.context)
            .then((product) =&gt; {
                this.product = product;
    
                // creates a repository which working with the associated route
                
                this.catRepository = this.repositoryFactory.create(
                    // `category`
                    this.product.categories.entity,
                
                    // `product/some-id/categories`    
                    this.product.categories.source
                );
            });
    },
    
    loadCategories() {
        this.catRepository
            .search(new Criteria(), this.context)
            .then((categories) =&gt; {
                this.categories = categories;
            });
    },
    
    addCategoryToProduct(category) {
        this.catRepository
            .assign(category.id, this.context)
            .then(this.loadCategories);
    },
    
    removeCategoryFromProduct(categoryId) {
        this.catRepository
            .delete(categoryId, this.context)
            .then(this.loadCategories);
    }
});</pre>

<p><br />
<strong>Working with local associations</strong><br />
In case of a new entity, the associations can not be send directly to the server using the repository, because the parent association isn&#39;t saved yet.</p>

<p>For this case the association can be used as storage as well and will be updated with the parent entity.</p>

<p>In the following examples, this.productRepository.save(this.product, this.context) will send the prices and category changes.</p>

<p><strong>Working with local OneToMany associations</strong></p>

<pre>
Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.productRepository = this.repositoryFactory.create(&#39;product&#39;);
    
        this.productRepository
            .get(&#39;some-id&#39;, this.context)
            .then((product) =&gt; {
                this.product = product;
    
                this.priceRepository = this.repositoryFactory.create(
                    // `product_price`
                    this.product.prices.entity,
                    
                    // `product/some-id/priceRules`
                    this.product.prices.source
                );
            });
    },
    
    loadPrices() {
        this.prices = this.product.prices;
    },
    
    addPrice() {
        const newPrice = this.priceRepository
            .create(this.context);
    
        newPrice.quantityStart = 1;
        // update some other fields
    
        this.product.prices.add(newPrice);
    },
    
    deletePrice(priceId) {
        this.product.prices.remove(priceId);
    },
    
    updatePrice(price) {
        // price entity is already updated and already assigned to product, no sources needed
    }
});</pre>

<p><br />
<strong>Working with local ManyToMany associations</strong></p>

<pre>
<strong>
</strong>Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.productRepository = this.repositoryFactory.create(&#39;product&#39;);
    
        this.productRepository
            .get(&#39;some-id&#39;, this.context)
            .then((product) =&gt; {
                this.product = product;
    
                // creates a repository which working with the associated route
                
                this.catRepository = this.repositoryFactory.create(
                    // `category`
                    this.product.categories.entity,
                    
                    // `product/some-id/categories`
                    this.product.categories.source
                );
            });
    },
    
    loadCategories() {
        this.categories = this.product.categories;
    },
    
    addCategoryToProduct(category) {
        this.product.categories.add(category);
    },
    
    removeCategoryFromProduct(categoryId) {
        this.product.categories.remove(categoryId);
    }
});</pre>

<p><br />
<strong>Working with version</strong><br />
The new data handling supports the php DAL versioning too. This allows the user to make changes that are not applied directly to the live shop. This is required when content such as products, CMS pages, orders are processed where the user needs the possibility to revert the changes.</p>

<pre>
Component.register(&#39;sw-show-case-list&#39;, {
    inject: [&#39;repositoryFactory&#39;, &#39;context&#39;],
    
    created() {
        this.productRepository = this.repositoryFactory.create(&#39;product&#39;);
    
        this.entityId = &#39;some-id&#39;;
    
        this.productRepository
            .createVersion(this.entityId, this.context)
            .then((versionContext) =&gt; {
                // the version context contains another version id
                this.versionContext = versionContext;
            })
            .then(() =&gt; {
                // association has a reference to this version context
                return this.productRepository
                    .get(this.entityId, this.versionContext);
            })
            .then((entity) =&gt; {
                this.product = entity;
                return entity;
            });
    },
    
    cancel() {
        this.productRepository.deleteVersion(this.entityId, this.versionContext.versionId, this.versionContext);
    },
    
    merge() {
        this.productRepository
            .save(this.product, this.versionContext)
            .then(() =&gt; {
                this.productRepository.mergeVersion(
                    this.versionContext.versionId, 
                    this.versionContext
                );
        });
    }
});</pre>

<h3>2019-04-02: Default constants removed</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We removed a ton of constants from our super global <strong>Defaults-object.</strong></p>

<p>Please rebase your branch and run <strong>phpstan</strong> to check that you don&#39;t use any of the removed constants.</p>

<p>If you use some <strong>stateMachineConstants</strong> -&gt;<br />
They are moved to its own classes:</p>

<p><strong>OrderStates</strong></p>

<ul>
	<li>Shopware\Core\Checkout\Order\Aggregate\OrderDelivery\OrderDeliveryStates</li>
	<li>Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates</li>
	<li>Shopware\Core\Checkout\Order\OrderStates</li>
</ul>

<p>&nbsp;If you used some other constants, you have to replace them by a query to get the correct <strong>Id</strong></p>

<h3>2019-04-01: sw-icon update</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>The icon system in the administration has been updated.</p>

<p>Please execute <strong>administration:install </strong>to install new dependencies.</p>

<p><strong>Usage</strong><br />
The open API of the <strong>&lt;sw-icon&gt; </strong>component has not been changed. You can use it as before.</p>

<p><strong>Adding or updating icons</strong></p>

<ul>
	<li>All SVG icons can now be found in <strong>/platform/src/Administration/Resources/administration/src/app/assets/icons/svg</strong> as separate files.</li>
	<li><strong>TLDR: To add a new icon simply add the icon SVG file to the mentioned direcory.</strong></li>
	<li>All icons have to be prefixed with <strong>icons-.</strong></li>
	<li>The file names come from the directory structure of our design library. The export via Sketch automatically gives us a file name like <strong>icons-default-action-bookmark</strong>.</li>
	<li><em>Please keep in mind that these icons are the core icons. Do never add random icons from the web or stuff like that! We always receive the icons from the design department with properly optimized SVG files. When you need a completely new core icon please talk to the design department first.</em></li>
	<li>All icons from this directory are automatically registered as small functional components which are automatically available when using <strong>&lt;sw-icon name=&quot;your-icon-name&quot;&gt;</strong>. The component gets its name from the SVG file name. This is why a correct name is really important.</li>
	<li>When updating an icon simply override the desired SVG file.</li>
</ul>

<p><strong>Icon demo</strong></p>

<ul>
	<li>New demo: <a href="https://component-library.shopware.com/#/icons/">https://component-library.shopware.com/#/icons/</a></li>
	<li>The icon demo is now part of the component library. It can also be found at the very bottom of the main menu. This is the source of truth from now on.</li>
	<li>No more separate demos for default and multicolor icons.</li>
	<li>The icon demo gets updated automatically when icons are added, removed or updated.</li>
</ul>

<p><strong>Chrome bug</strong><br />
The icon bug in Google Chrome has been fixed. The SVG&#39;s source code is now directly inside the document. The use of an external SVG sprite is no longer in place. This caused the rendering issues under some circumstances in Vue.</p>

<p><strong>Why we made this change</strong></p>

<ul>
	<li>Easier workflow to add or update icons</li>
	<li>Inline SVGs do fix the Chrome bug</li>
	<li>No more dependencies of third party grunt tasks to generate the icon sprite</li>
	<li>No grunt dependency to build the icon demo.</li>
	<li>No extra request from the browser to get the icon sprite</li>
	<li>No extra repository required</li>
</ul>

<h3>2019-04-01: Payment handler exception</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>Payment handler are now able to throw special exceptions if certain error cases occur.</p>

<ul>
	<li><strong>Shopware\Core\Checkout\Payment\Cart\PaymentHandler\SynchronousPaymentHandlerInterface::pay </strong>should throw the<strong> SyncPaymentProcessException</strong> if something goes wrong.</li>
	<li>Same for the <strong>Shopware\Core\Checkout\Payment\Cart\PaymentHandler\AsynchronousPaymentHandlerInterface::pay</strong> Throw an <strong>AsyncPaymentProcessException</strong> e.g. if a call to an external API fails</li>
	<li>The finalize method of the <strong>AsynchronousPaymentHandlerInterface</strong> could also throw an <strong>AsyncPaymentFinalizeException</strong>. Additionally it could throw a <strong>CustomerCanceledAsyncPaymentException</strong> if the customer canceled the process on the payment provider page.</li>
</ul>

<p>In every case, Shopware catches these exceptions and set the transaction state to <strong>canceled</strong> before the exceptions are thrown again. So a caller of the Shopware pay API route will get an exception message, if something goes wrong during the payment process and could react accordingly.</p>

<p>Soonish it will be possible to transform the order into a cart again and let the customer update the payment method or something like that. Afterwards the order will be updatet und persisted again.</p>

<p>Have a look at the <a href="https://github.com/shopware/platform/blob/master/src/Docs/_new/4-how-to/010-payment-plugin.md">Docs</a> or at our <a href="https://github.com/shopwareLabs/SwagPayPal/blob/master/Core/Checkout/Payment/Cart/PaymentHandler/PayPalPayment.php">PayPal Plugin</a> for examples</p>

<h2>March 2019</h2>

<h3>2019-03-29: Exception Locations</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>Just removed the last of the global exceptions. From now on, please move custom exceptions into the <strong>module that throws it.</strong></p>

<p>For example:</p>

<ul>
	<li>Shopware\Core\Checkout\Cart\Exception</li>
	<li>Shopware\CoreFrmaework\DataAbstractionLayer\Exception</li>
</ul>

<p>Not</p>

<ul>
	<li>Shopware\Core\Checkout\Exception</li>
	<li>Shopware\Core\Content\Exception</li>
</ul>

<p><br />
In Perspective all Exception will move to a <strong>\Exception</strong> Folder, so pleas do no longer put them inline with the executing classes</p>

<p><em>FYI: There is a test that checks this :zwinkern:</em></p>

<h3>2019-03-29: Backend UUID</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>The Uuid class was moved from <strong>FrameworkStruct\Uuid </strong>to<strong> Framework\Uuid\Uuid </strong>please adjust your branches.</p>

<p><strong>Changes:</strong></p>

<ul>
	<li>The new class does no longer support a<strong> ::uuid4() </strong>please use <strong>::randomHex() </strong>or <strong>::randomBytes() </strong>instead</li>
	<li>The string format (with the dashes like <strong>123456-1234-1234-1234-12345679812</strong>) is no longer supported, methods are removed</li>
	<li>The Exceptions moved to <strong>Framework\Uuid\Exception&nbsp;</strong></li>
</ul>

<p><br />
Backwards Compatibility:</p>

<p>You can still use the old class, but it is deprecated and will be removed next week.</p>

<h3>2019-03-28: Validation / input validation</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p><strong>#1 Request / Query data</strong><br />
Request data from either the body (POST) or from the query string (GET) is now wrapped in a <strong>DataBag</strong>. It&#39;s an extension to Symfony&#39;s <strong>ParameterBag</strong> with some sugar for arrays. This allows you to access nested arrays more fluently:</p>

<pre>
// before:
$bag-&gt;get(&#39;billingAddress&#39;)[&#39;firstName&#39;]

</pre>

<p>&nbsp;</p>

<pre>
// after:
$bag-&gt;get(&#39;billingAddress&#39;)-&gt;get(&#39;firstName&#39;)</pre>

<p><br />
To prevent boilerplate code like <strong>new DataBag($request-&gt;request-&gt;all()); </strong>you can type-hint the controller arguments to either <strong>RequestDataBag</strong> and <strong>QueryDataBag</strong> which automatically creates a DataBag with the data from the request.</p>

<p><strong>#2 DataValidation</strong><br />
We leverage Symfony&#39;s validation constraints for our input validation. They already implement a ton of constraints like NotBlank, Length or Iban and they provide a documented way on how to add custom constraints.</p>

<p><strong>#2.1 DATA VALIDATION DEFINITION</strong><br />
We&#39;ve introduced a DataValidationDefinition which contains the validation constraints for a given input.</p>

<p><strong>Example</strong></p>

<pre>
$definition = new DataValidationDefinition(&#39;customer.update&#39;);
$definition-&gt;add(&#39;firstName&#39;, new NotBlank())
    -&gt;add(&#39;email&#39;, new NotBlank(), new Email())
    -&gt;add(&#39;salutationId&#39;, new NotBlank(), new EntityExists(&#39;entity&#39; =&gt; &#39;salutation&#39;, &#39;context&#39; =&gt; $context));
    
// nested validation
$billingValidation = new DataValidationDefinition(&#39;billing.update&#39;);
$billingValidation-&gt;add(&#39;street&#39;, new NotBlank());

$definition-&gt;addSub(&#39;billingAddress&#39;, $billingDefinition);
You can now pass the definition with your data to the DataValidator which does the heavy lifting.

// throws ConstraintViolationException
$this-&gt;dataValidator-&gt;validate($bag-&gt;all(), $definition);

// gets all constraint violations
$this-&gt;dataValidator-&gt;getViolations($bag-&gt;all(), $definition);</pre>

<p><br />
<strong>#2.2 EXTENDING EXISTING/RECURRING VALIDATION DEFINITIONS</strong><br />
If you need the same validation over and over again, you should consider a ValidationService class which implements the ValidationServiceInterface. This interface provides to methods for creating and updating recurring input data, like addresses.</p>

<p>You may decorate the services but we prefer the way using events. So the calling class should throw an BuildValidationEvent which contains the validation definition and the context. As a developer, you can subscribe to framework.validation.VALIDATION_NAME (e.g. framework.validation.address_create) to extend the existing validation.</p>

<p><strong>#2.3 EXTENDING THE DATA MAPPING TO DAL SYNTAX</strong><br />
After validation, your data needs to be mapped to the syntax of the DAL to do a successful write. After the data has been mapped to the DAL syntax, you should throw a DataMappingEvent so that plugin developers can modify the payload to be written.</p>

<p><strong>Example</strong>:</p>

<pre>
$mappingEvent = new DataMappingEvent(CustomerEvents::MAPPING_CUSTOMER_PROFILE_SAVE, $bag, $mapped, $context-&gt;getContext());

$this-&gt;eventDispatcher-&gt;dispatch($mappingEvent-&gt;getName(), $mappingEvent);

$mapped = $mappingEvent-&gt;getOutput();</pre>

<p><br />
The $mapped variable will then be passed to the DAL repository.</p>

<h3>2019-03-28: Payment refactoring</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We deleted the entity properties:</p>

<ul>
	<li>template</li>
	<li>class</li>
	<li>percentageSurcharge</li>
	<li>absoluteSurcharge</li>
	<li>surchargeText</li>
</ul>

<p>and renamed the <strong>technicalName</strong> to <strong>handlerIdentifier</strong>, which isn&acute;t unique anymore.</p>

<p>The <strong>handlerIdentifier</strong> is only internal and can not be written by the API. It contains the class of the identifier. If a plugin is created via the admin, the <strong>Shopware\Core\Checkout\Payment\Cart\PaymentHandler\DefaultPayment</strong> handler will be choosed.</p>

<p>Also we divided the <strong>PaymentHandlerInterface</strong> into two payment handler interfaces:</p>

<ul>
	<li>AsynchronousPaymentHandlerInterface</li>
	<li>SynchronousPaymentHandlerInterface</li>
</ul>

<p>and also added the two new structs:</p>

<ul>
	<li>AsyncPaymentTransactionStruct</li>
	<li>SyncPaymentTransactionStruct</li>
</ul>

<p>The <strong>AsynchronousPaymentHandlerInterface</strong> has a <strong>finalize</strong> Method and the <strong>pay</strong> Method returns a <strong>RedirectResponse</strong>. In the <strong>SynchronousPaymentHandlerInterface</strong> we only have the <strong>pay</strong> Methods wich has no return.</p>

<p>Another change is a decoration of the payment repository which prevents to delete a plugin payment via API. Payments without a plugin id can be deleted via API. For plugin payments deletions, the plugin itself has to use the new method <strong>internalDelete</strong>, which uses the normal undecorated delete method without restrictions.</p>

<h3>2019-03-28: Exception conventions</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p><strong>Error codes</strong><br />
Exceptions are not translated when they are thrown. There there must be an identifier to translate them in the clients. Every exception in Shopware should implement <strong>ShopwareException</strong> or better extend from <strong>ShopwareHttpException</strong>. The interface now requires an getErrorCode() method, which returns an unique identifier for this exception.</p>

<p>The identifier is built of two components. Domain and error summary separated by an underscore, all uppercase and spaces replaced by underscores. For example: <strong>CHECKOUT__CART_IS_EMPTY</strong></p>

<p><strong>Placeholder</strong><br />
In addition, the placeholders in exceptions have been implemented. The ShopwareHttpException constructor has 2 parameters, a message and an array of parameters to be replace within the message. <strong>Please do not use sprintf() anymore!</strong></p>

<p><strong>Example:</strong></p>

<pre>
parent::__construct(
&nbsp;&nbsp; &nbsp;&#39;The type &quot;{{ type }}&quot; is not supported.&#39;,&nbsp;
&nbsp;&nbsp; &nbsp;[&#39;type&#39; =&gt; &#39;foo&#39;]
);</pre>

<h3>2019-03-27: BC: SourceContext removed</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We&#39;ve removed the <strong>SourceContext</strong> as it was global mutable State.</p>

<p>Now the <strong>Context</strong> has a <strong>Source</strong>, that is either a <strong>SystemSource</strong>, <strong>AdminApiSource</strong> or <strong>SalesChannelSource</strong>.</p>

<p>If you want to get the <strong>SalesChannel</strong> or user from the <strong>Context</strong> you have to explicitly check the <strong>Source</strong> as these things aren&#39;t always set.</p>

<p>Don&#39;t use the shortcut function to get the <strong>SalesChannelId</strong> od <strong>userId</strong> directly on the Context-Object, as these will be removed soon.</p>

<h3>2019-03-27: Plugin system: New flag managed_by_composer on plugin table</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>The entity for plugins got a new boolean field <strong>managedByComposer</strong> which determines if a plugin is required with composer. The field is set during <strong>bin/console plugin:refresh</strong></p>

<p>So if you are currently developing or working with plugins you might need to recreate your database.</p>

<h3>2019-03-20: PaymentTransactionStruct changed</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We changed the contents of the <strong>\Shopware\Core\Checkout\Payment\Cart\PaymentTransactionStruct</strong></p>

<p>Now it contains the whole <strong>\Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity </strong>object, from which you should get all necessary information about the order, customer and transaction. The second property is the returnUrl.</p>

<p>Due to this change, you need to adjust your PaymentHandler. Have a look at our PayPal plugins which changes are necessary: <a href="https://github.com/shopwareLabs/SwagPayPal/commit/af5532361be7d0d54c055896a340ee7574df2d66">https://github.com/shopwareLabs/SwagPayPal/commit/af5532361be7d0d54c055896a340ee7574df2d66</a></p>

<h3>2019-03-20: PaymentMethodEntity changed</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We changed the properties of <strong>src/Core/Checkout/Payment/PaymentMethodEntity.php</strong> from <strong>additionalDescription</strong> to <strong>description</strong> and <strong>surcharge_string</strong> to <strong>surcharge_text</strong>. <strong>surcharge_text</strong> is also now translateable.</p>

<p>Further changes of the <strong>PaymentHandler</strong> and the <strong>PaymentMethodEntity</strong> are in development.</p>

<h3>2019-03-20: !!! Public admin component library available !!!</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>The component library is now public on <a href="https://component-library.shopware.com">https://component-library.shopware.com</a> !</p>

<h3>2019-03-20: Major issues fixed in admin data handling</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We fixed two issues in the current data handling of the administration.</p>

<p><strong>Hydration of associated entities as instances of EntityProxy</strong><br />
Because of an issue with the method of deep copying objects, the hydrated asssociations of an entity were simple objects. We now fixed this behaviour, so the N:M relations are also instances of EntityProxy. The hydrated associations in the draft of the entity keep the reference to the entity in the association store.</p>

<p><strong>BEFORE</strong></p>

<pre>
// EntityProxy
product = {
&nbsp;&nbsp; &nbsp;categories: [
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Object {}
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Object {}
&nbsp;&nbsp; &nbsp;]
}</pre>

<p><strong>AFTER</strong></p>

<pre>
// EntityProxy
product = {
&nbsp;&nbsp; &nbsp;categories: [
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Proxy {}
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Proxy {}
&nbsp;&nbsp; &nbsp;]
}</pre>

<h3>2019-03-19: First plugin-manager version</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>The first Plugin-Manager version is now merged, but it&#39;s behind the <strong>NEXT-1223</strong> feature flag.</p>

<p>Before you can use the Plugin-Manager, you have to set your host in the shopware.yml file. Additionally, you have to change the Framework Version in Framework.php to a version that the SBP knows.</p>

<p>You can now upload zips, install, deinstall, update, activate and deactivate plugins in the Administration instead of using the CLI.</p>

<p>Furthermore, it is possible to download and update plugins directly from the Community Store if you have a license for that plugin in your account and you are logged with your Shopware ID.</p>

<h3>2019-03-19: Salutations</h3>

<p>We changed the salutation property of <strong>Customer</strong>, <strong>CustomerAddress</strong>, <strong>OrderCustomer</strong> and <strong>OrderAddress</strong> from <strong>StringField</strong> to a reference of the <strong>SalutationEntity</strong>. Since this property is now required to all of these entities, you have to provide a salutationId. In some cases these constants can be helpful for that:</p>

<ul>
	<li>Defaults::SALUTATION_ID_MR</li>
	<li>Defaults::SALUTATION_ID_MRS</li>
	<li>Defaults::SALUTATION_ID_MISS</li>
	<li>Defaults::SALUTATION_ID_DIVERSE</li>
	<li>Defaults::SALUTATION_KEY_MR</li>
	<li>Defaults::SALUTATION_KEY_MRS</li>
	<li>Defaults::SALUTATION_KEY_MISS</li>
	<li>Defaults::SALUTATION_KEY_DIVERSE</li>
</ul>

<p>Additionally you can now easily format a full name with salutation, title and name using either the salutation mixin via <strong>salutation(entity, fallbackString) </strong>or the filter in the twig files via e.g. <strong>{{ customer | salutation }}</strong>or <strong>{{ customer | salutation(fallbackString) }}</strong>. The only requirement for that is to use an entity like Customer which contains firstname, lastname, title and/or a salutation.</p>

<h3>2019-03-14: sw-tree refactoring</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>The <strong>sw-tree</strong> was refactored again, due to the changing of the sorting.</p>

<p>To use the tree, each item should have an <strong>afterIdProperty</strong> which should contain the id of the element which is before the current item.</p>

<p>You now do not have to deconstruct the template in your component anymore to pass your own wording and functions.</p>

<p>The tree has its own <strong>addElement</strong> and <strong>addSubElement</strong> methods which need two methods from the parent-component: <strong>createNewElement</strong>, which needs to return a new entity and <strong>getChildrenFromParent</strong>, which needs to load child-items from the passed <strong>parentId</strong>.</p>

<p>If you delete an item,<strong> delete-element</strong> will be emited and can be used in the parent.</p>

<p>To get translations you can pass the <strong>translationContext</strong> prop, which is by default <strong>sw-tree</strong>. To get your desired translations you can simply ducplicate the <strong>sw-tree </strong>translations and edit them to your needs and pass <strong>sw-yourcomponen</strong>t to the prop.</p>

<p>To link the elements you can use the <strong>onChangeRoute</strong> prop which needs to be a function and is applied to all <strong>sw-tree-items</strong></p>

<p>If you need to disable the contextmenu you can pass <strong>disableContextMenu</strong></p>

<p>A visual example can be found in the <strong>sw-category-tree</strong></p>

<h3>2019-03-12: Updates for upload handling</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>In order to react to upload events and errors globally, we made some changes how uploads are stored and run.</p>

<p>Events are now fired directly by the upload store and the &lt;sw-media-upload&gt; component now only handles file and url objects to create upload data. So it is not possible anymore to subscribe to the sw-media-upload-new-uploads-added, sw-media-upload-media-upload-success and sw-media-upload-media-upload-failure events from it.</p>

<p><strong>Handling upload events with vue.js</strong><br />
We added an additional component &lt;sw-upload-store-listener&gt; in order to take over all the listener registration an most of the event handling. The upload handler has only two properties.</p>

<p>uploadTag: String - the upload tag you want to listen to<br />
autoupload: Boolean indicating that the upload added events should be skiped and youre only interested in when the upload was successfull or errored<br />
The component emits vue.js events back to your wrapping component</p>

<p><strong>sw-media-upload-added:</strong> Object { UploadTask[]: data } - this will be skipped if you set autoupload to true<br />
<strong>sw-media-upload-finished:</strong> Object { string: targetId }<br />
<strong>sw-media-upload-failed:</strong> UploadTask<br />
In most cases you will set autoupload to true but it isn&#39;t the default. Sometimes you want to do additional work, before the the real upload process starts (e.g. creating associations). To do so listen to the sw-media-upload-added event use the data array to get the media ids of the entities that are just created for the upload.</p>

<p><strong>Common Example</strong><br />
The following code snippet is simplified from the sw-media-index component</p>

<pre>
// template
&lt;sw-media-upload
&nbsp; &nbsp; variant=&quot;compact&quot;
&nbsp; &nbsp; :targetFolderId=&quot;routeFolderId&quot;
&nbsp; &nbsp; :uploadTag=&quot;uploadTag&quot;&gt;
&lt;/sw-media-upload&gt;
&lt;sw-upload-store-listener
&nbsp; &nbsp; :uploadTag=&quot;uploadTag&quot;
&nbsp; &nbsp; @sw-media-upload-added=&quot;onUploadsAdded&quot;
&nbsp; &nbsp; @sw-media-upload-finished=&quot;onUploadFinished&quot;
&nbsp; &nbsp; @sw-media-upload-failed=&quot;onUploadFailed&quot;&gt;
&lt;/sw-upload-store-listener&gt;

// .js
onUploadsAdded({ data }) {
&nbsp; &nbsp; data.forEach((upload) =&gt; {
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;// do stuff with each upload that was added
&nbsp; &nbsp; });

&nbsp; &nbsp; // run the actual upload process
&nbsp; &nbsp; this.uploadStore.runUploads(this.uploadTag);
},

onUploadFinished({ targetId }) {
&nbsp; &nbsp; // refresh media entity
&nbsp; &nbsp; this.mediaItemStore.getByIdAsync(targetId).then((updatedItem) =&gt; {
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;// do something with the refreshed entitity;
&nbsp; &nbsp; });
}

// if your are only interested in the target entity&#39;s id
// you can use destructuring
onUploadFailed({ targetId }) {
&nbsp; &nbsp; // tidy up
&nbsp; &nbsp; this.mediaItemStore.getByIdAsync(targetId).then((updatedMedia) =&gt; {
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;if (!updatedMedia.hasFile) {
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;updatedMedia.delete(true);
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;}
&nbsp; &nbsp; });
}</pre>

<p><strong>Subscribe to the store manually</strong><br />
You can also subscribe to the store directly but it is not the preferred way. You can add and remove your listener with the following methods:</p>

<p>addListener(string: uploadTag, function: callback)<br />
removeListener(string: uploadTag, funtion: callback)<br />
addDefaultListener(function: callback)<br />
removeDefaultListenre(function: callback)<br />
The store will pass you a single object back to your callback when an upload event occurs:</p>

<pre>
uploadStore = State.getStore(&#39;upload&#39;);
uploadStore.addListener(&#39;my-upload-tag&#39;, myListener);

function myListener({action, uploadTag, payload }) {...}</pre>

<p>The action and payload is similar to the vue.js event name and $event data described above.</p>

<h3>2019-03-12: Number ranges added</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We implemented a configurable number range.</p>

<p>Number ranges are defined unique identifiers for specific entities.</p>

<p>The new NumberRangeValueGenerator is used to generate a unique identifier for a given entity with a given configuration.</p>

<p>The configuration will be provided in the administration where you can provide a pattern for a specific entity in a specific sales channel.</p>

<p>You can reserve a new value for a number range by calling the route /api/v1/number-range/reserve/{entity}/{salesChannelId} with the name of the entity like product or order and, for sales channel dependent number ranges, also the salesChannelId</p>

<p>In-Code reservation of a new value for a number range can be done by using the NumberRangeValueGenerator method getValue(string $definition, Context $context, ?string $salesChannelId) directly.</p>

<p><strong>PATTERNS</strong><br />
Build-In patterns are the following:</p>

<p>increment(&#39;n&#39;): Generates a consecutive number, the value to start with can be defined in the configuration</p>

<p>date(&#39;date&#39;,&#39;date_ymd&#39;): Generates the date by time of generation. The standard format is &#39;y-m-d&#39;. The format can be overwritten by passing the format as part of the pattern. The pattern date_ymd generates a date in the Format 190231. This pattern accepts a PHP Dateformat-String</p>

<p><strong>PATTERN EXAMPLE</strong><br />
Order{date_dmy}_{n} will generate a value like Order310219_5489</p>

<p><strong>ValueGeneratorPattern</strong></p>

<p>The ValueGeneratorPattern is a resolver for a part of the whole pattern configured for a given number range.</p>

<p>The build-in patterns mentioned above have a corresponding pattern resolver which is responsible for resolving the pattern to the correct value.</p>

<p>A ValueGeneratorPattern can easily be added to extend the possibilities for specific requirements.</p>

<p>You only need to derive a class from ValueGeneratorPattern and implement your custom rules to the resolve-method.</p>

<p><strong>IncrementConnector</strong><br />
<br />
The increment pattern is somewhat special because it needs to communicate with a persistence layer in some way.</p>

<p>The IncrementConnector allows you to overwrite the connection interface for the increment pattern to switch to a more perfomant solution for this specific task.</p>

<p>If you want to overwrite the IncrementConnector you have to implement the IncrementConnectorInterface in your new connector class and register your new class with the id of the interface.</p>

<p>&nbsp;</p>

<pre>
&lt;service class=&quot;MyNewIncrementConnector&quot; id=&quot;Shopware\Core\System\NumberRange\ValueGenerator\IncrementConnectorInterface&quot;&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;tag name=&quot;shopware.value_generator_connector&quot;/&gt;
&lt;/service&gt;</pre>

<h3>2019-03-12: Details column removed from OrderTransaction</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We removed the details column from the order_transaction table. It was introduced in the past, to store additional data to the transaction, e.g. from external payment providers. This is now unnecessary since the introduction of the custom field. If you stored data to the details field, create a new custom field and store the data in this custom field field. An example migration could be found in our <a href="https://github.com/shopwareLabs/SwagPayPal/commit/a09beec33c5ebe8247d259e970dfcc09ee9c8f13">PayPal integration</a></p>

<h3>2019-03-11: sw-data-grid update</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<ul>
	<li>The prop &quot;identifier&quot; is no longer required. When no identifier is set the grid will not save any settings to the localStorage.</li>
	<li>The prop &quot;dataSource&quot; now also accepts Objects. (This is needed for the new data handling with &quot;repository&quot;)</li>
	<li>Columns do now have a resize limit and can not be resized smaller than 65 pixel.</li>
	<li>Accidental sorting when doing a column resize should now be fixed.</li>
</ul>

<h3>2019-03-11: New component sw-data-grid</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>The sw-data-grid is a new component to render tables with data. It works similar to the sw-grid component but it has some additional features like hiding columns or scrolling horizontally.</p>

<p>To prevent many data lists from breaking the sw-data-grid is introduced as a new independent component. The main lists for products, orders and customers are already using the sw-data-grid component. Other lists like languages or manufactureres will be migrated in the future.</p>

<p><strong>How to use it</strong><br />
To render a very basic data grid you need two mandatory props:</p>

<p><strong>dataSource: </strong>Result from Store getList<br />
<strong>columns:</strong> Array of columns which should be displayed</p>

<pre>
&lt;sw-data-grid
&nbsp; &nbsp; dataSource=&quot;products&quot;
&nbsp; &nbsp; columns=&quot;productColumns&quot;&gt;
&lt;sw-data-grid&gt;</pre>

<p><strong>How to configure columns</strong></p>

<pre>
methods: {
&nbsp;&nbsp; &nbsp;// Define columns
&nbsp;&nbsp; &nbsp;getProductColumns() {
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;return [{
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;property: &#39;name&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;label: &#39;Name&#39;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;}, {
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;property: &#39;price.gross&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;label: &#39;Price&#39;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;}]
&nbsp;&nbsp; &nbsp;}
}

computed: {
&nbsp;&nbsp; &nbsp;// Access columns in the template
&nbsp;&nbsp; &nbsp;productColumns() {
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;return getProductColumns();
&nbsp;&nbsp; &nbsp;}
}</pre>

<p>Theoretically, you could define your columns directly in the template but it is recommended to do this inside your JavaScript. The extra method allows plugin developers to exdend the columns.</p>

<p><strong>AVAILABLE COLUMN PROPERTIES</strong></p>

<pre>
{
&nbsp;&nbsp; &nbsp;property: &#39;name&#39;,
&nbsp;&nbsp; &nbsp;label: &#39;Name&#39;
&nbsp;&nbsp; &nbsp;dataIndex: &#39;name&#39;,
&nbsp;&nbsp; &nbsp;align: &#39;right&#39;,
&nbsp;&nbsp; &nbsp;inlineEdit: &#39;string&#39;,
&nbsp;&nbsp; &nbsp;routerLink: &#39;sw.product.detail&#39;,
&nbsp;&nbsp; &nbsp;width: &#39;auto&#39;,
&nbsp;&nbsp; &nbsp;visible: true,
&nbsp;&nbsp; &nbsp;allowResize: true,
&nbsp;&nbsp; &nbsp;primary: true,
&nbsp;&nbsp; &nbsp;rawData: false
}</pre>

<p><strong>property (string, required)</strong></p>

<p>The field/property of the entity that you want to render.</p>

<p><strong>label (string, recommended)</strong></p>

<p>The label text will be shown in the grid header and the settings panel. The grid works without the label but the header and the settings panel expect a label and will show empty content when the label is not set. The settings panel and the header should be set to hidden when using no label.</p>

<p><strong>dataIndex (string, optional)</strong></p>

<p>Define a property that should be sorted when clicking the grid header. This works similar to sw-grid. The sorting is active when dataIndex ist set. The sortable property is not needed anymore.</p>

<p><strong>align (string, optional)</strong></p>

<p>The alignment of the cell content.</p>

<p>Available options: left, right, center<br />
Default: left<br />
<strong>inlineEdit (string, optional)</strong></p>

<p>Activates the inlineEdit for the column. The sw-data-grid can display default inlineEdit fields out of the box. At the moment this is only working with very basic fields and properties which are NOT an association. However, you have the possibility to render custom inlineEdit fields in the template via slot.</p>

<p>Available options: string, boolan, number<br />
<strong>routerLink (string, optional)</strong></p>

<p>Change the cell content text to a router link to e.g. redirect to a detail page. The router link will automatically get a parameter with the id of the current grid item. If you want to have different router links you can render a custom &lt;router-link&gt; via slot.</p>

<p><strong>width (string, optional)</strong></p>

<p>The width of the column. In most cases the grid gets it&#39;s columns widths automatically based on the content. If you wan&#39;t to give a column a minimal width e.g. 400px this can be helpful.</p>

<p>Default: auto<br />
<strong>visible (boolean, optional)</strong></p>

<p>Define if a column is visible. When it is not visible initially the user could toggle the visibility when the grid settings panel is activated.</p>

<p>Default: true<br />
<strong>allowResize (boolean, optional)</strong></p>

<p>When true the column header gets a drag element and the user is able to resize the column width.</p>

<p>Default: false<br />
<strong>primary (boolean, recommended)</strong></p>

<p>When true the column can not be hidden via the grid settings panel. This is highly recommended if the settings panel is active.</p>

<p>Default: false<br />
<strong>rawData (boolean, otional)</strong></p>

<p>Experimental: Render the raw data instead of meta.viewData</p>

<p><strong>Available props</strong></p>

<p><br />
<strong>dataSource (array/object, required)</strong></p>

<p>Result from Store getList</p>

<p><strong>columns (array, required)</strong></p>

<p>Array of columns which should be displayed</p>

<p><strong>identifier (string, required)</strong></p>

<p>A unique ID is needed for saving columns in the localStorage individually for each grid. When no identifier is set the grid will not save any settings like column visibility or column order.</p>

<p><strong>showSelection (boolean, optional)</strong></p>

<p>Shows a column with selection checkboxes.</p>

<p><strong>showActions (boolean, optional)</strong></p>

<p>Shows a column with an action menu.</p>

<p><strong>showHeader (boolean, optional)</strong></p>

<p>Shows the grid header</p>

<p><strong>showSettings (boolean, optional)</strong></p>

<p>Shows a small settings panel. Inside the panel the user can control the column order and visibility.</p>

<p><strong>fullPage (boolean, optional)</strong></p>

<p>Positions the grid absolute for large lists.</p>

<p><strong>allowInlineEdit (boolean, optional)</strong></p>

<p>Defines if the grid activates the inline edit mode when the user double clicks a row.</p>

<p><strong>allowColumnEdit (boolean, optional)</strong></p>

<p>Shows a small action menu in all column headers.</p>

<p><strong>isLoading (boolean, recommended)</strong></p>

<p>The isLoading state from the listing call e.g. Store getList</p>

<p><strong>skeletonItemAmount (number, optional)</strong></p>

<p>The number of skeleton items which will be displayed when the grid is currently loading.</p>

<p><strong>Available slots</strong></p>

<ul>
	<li>actions (scoped slot width &quot;items&quot;)</li>
	<li>action-modals (scoped slot width &quot;items&quot;)</li>
	<li>pagination</li>
</ul>

<p><br />
<strong>DYNAMIC SLOTS FOR COLUMN CONTENT</strong><br />
Every column creates a dynamic slot in which you can put custom HTML. This dynamic slots are prefixed with &quot;column-&quot; followed by the property of the column you want to change.</p>

<pre>
&lt;sw-data-grid
&nbsp;&nbsp; &nbsp;:dataSource=&quot;products&quot;
&nbsp;&nbsp; &nbsp;:columns=&quot;productColumns&quot;
&nbsp;&nbsp; &nbsp;:identifier=&quot;my-grid&quot;&gt;

&nbsp;&nbsp; &nbsp;&lt;template slot=&quot;column-firstName&quot; slot-scope=&quot;{ item }&quot;&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;{{ item.salutation }} {{ item.firstName }} {{ item.lastName }}
&nbsp;&nbsp; &nbsp;&lt;/template&gt;
&lt;/sw-data-grid&gt;</pre>

<p>The dynamic slots provide the following properties via slot-scope:</p>

<p><strong>item</strong></p>

<p>The current record</p>

<p><strong>column</strong></p>

<p>The current column</p>

<p><strong>compact</strong></p>

<p>Info if the grid is currently in compact mode.</p>

<p><strong>isInlineEdit</strong></p>

<p>Is the inline edit active for the current column. This can be helpful for customized form components inside the inline edit cell.</p>

<h3>2019-03-08: Module specific snippets</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We had the problem that our two big snippets files for the administration caused a bunch of merge conflicts in the past.</p>

<p>Now it&#39;s possible to register module specific snippets, e.g. each module can have their own snippet files.</p>

<pre>
import deDE from &#39;./snippet/de_DE.json&#39;;
import enGB from &#39;./snippet/en_GB.json&#39;;

Module.register(&#39;sw-configuration&#39;, {
&nbsp;&nbsp; &nbsp;// ...

&nbsp; &nbsp; snippets: {
&nbsp; &nbsp; &nbsp; &nbsp; &#39;de-DE&#39;: deDE,
&nbsp; &nbsp; &nbsp; &nbsp; &#39;en-GB&#39;: enGB
&nbsp; &nbsp; },

&nbsp;&nbsp; &nbsp;// ...
});</pre>

<p>The module has a new property called snippetswhich should contain the ISO codes for different languages.</p>

<p>Inside the JSON files you still need the module key in place:</p>

<pre>
{
&nbsp; &nbsp;&quot;sw-product&quot;: { ... }
}</pre>

<p>The usage inside components and component templates haven&#39;t changed.</p>

<h3>2019-03-06: JsonField-Serializer changes</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>It&#39;s already possible to define types for the values in the json object by passing an array of Fields into propertyMapping. The values are then validated and encoded by the corresponding FieldSerializer.</p>

<p><strong>We implemented two changes:</strong></p>

<ul>
	<li>the decode method now calls the fields decode method and formats \DateTime as \DateTime::ATOM (Example: BoolField values are now decoded as true/false instead of 0/1)</li>
	<li>the JsonFieldAccessorBuilder now casts according to the matching types on the SQL side. That means it&#39;s now possible to correclty filter and aggregate on many typed fields. The following fields are supported:</li>
	<li>IntField</li>
	<li>FloatField</li>
	<li>BoolField</li>
	<li>DateField</li>
</ul>

<p>All other Fields are handled as strings.</p>

<h3>2019-03-05: Make entityName property private</h3>

<p>In order to avoid naming conflicts wirth entities, that define a entityName field, we decided to mark the entityName property in EntityStore and EntityProxy as private by adding a preceding undersore.</p>

<p>In the most cases this will not affect you directly since you should always know, what entities you&#39;re working on. However in mixed lists it can be usefull to make decisions depending on the type. To do so use the new getEntityName() function provided by proxy and store.</p>

<pre>
//example testing vue.js properties
props: {
&nbsp; &nbsp; myEntity: {
&nbsp; &nbsp; &nbsp; &nbsp; type: Object,
&nbsp; &nbsp; &nbsp; &nbsp; required: true,
&nbsp; &nbsp; &nbsp; &nbsp; validator(value) &nbsp;{
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; return value.getEntityName() === &#39;some_entity_name&#39;;
&nbsp; &nbsp; &nbsp; &nbsp; }
&nbsp; &nbsp; }
}</pre>

<h3>2019-03-06: CustomFields</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We added an easy way to add custom fields to entities. The CustomField is like the JsonField only dynamically typed. To save custom fields to entities you first have to define the custom field:</p>

<pre>
$customFieldsRepository-&gt;create([[
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;id&#39; =&gt; &#39;&lt;uuid&gt;&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;name&#39; =&gt; &#39;sw_test_float&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;type&#39; =&gt; CustomFieldType::Float,
&nbsp;&nbsp; &nbsp;]],
&nbsp;&nbsp; &nbsp;$context
);
</pre>

<p>Then you can save it like a normal json field</p>

<pre>
$entityRepository-&gt;update([[
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;id&#39; =&gt; &#39;&lt;entity id&#39;&gt;&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;customFields&#39; =&gt; [
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;sw_test_float&#39; =&gt; 10.1
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;]
&nbsp;&nbsp; &nbsp;]],
&nbsp;&nbsp; &nbsp;$context
);</pre>

<p>Unlike the JsonField, the CustomField patchs the data instead of replacing it completely. So you dont need to send the whole object to update one property.</p>

<h3>2019-03-05: New tab component</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>The new tab component got a redesign. It supports now horizontal and vertical mode. The vertical mode looks and works like the side-navigation component. This is the reason why it was replaced with this component. You can switch between a left and right alignment.</p>

<p>You can use the sw-tabs-item component for each tab item. It accepts a vue route. When no route is provided then it will be used like a normal link which you can use for every case.</p>

<pre>
&lt;sw-tabs isVertical small alignRight&gt;

&nbsp; &nbsp; &lt;sw-tabs-item :to=&quot;{ name: &#39;sw.explore.index&#39; }&quot;&gt;
&nbsp; &nbsp; &nbsp; &nbsp; Explore
&nbsp; &nbsp; &lt;/sw-tabs-item&gt;

&nbsp; &nbsp; &lt;sw-tabs-item href=&quot;https://www.shopware.com&quot;&gt;
&nbsp; &nbsp; &nbsp; &nbsp; My Plugins
&nbsp; &nbsp; &lt;/sw-tabs-item&gt;

&lt;/sw-tabs&gt;
</pre>

<h3>2019-03-01: NPM dependency</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>Recently we used an npm feature (clean-install) which is available since version 6.5.0</p>

<p>We already updated the docs accordingly, but the npm dependency in the package.json was still wrong. this is fixed now. so please check the npm version on your machine!</p>


<h3>2019-03-01: New code style fixer rules</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>We added the following new rules to our coding style rule set</p>

<ul>
	<li>NoUselessCommentFixer</li>
	<li>PhpdocNoSuperfluousParamFixer</li>
	<li>NoImportFromGlobalNamespaceFixer</li>
	<li>OperatorLinebreakFixer</li>
	<li>PhpdocNoIncorrectVarAnnotationFixer</li>
	<li>NoUnneededConcatenationFixer</li>
	<li>NullableParamStyleFixer</li>
</ul>

<p>Have a look here <a href="https://github.com/kubawerlos/php-cs-fixer-custom-fixers#fixers">https://github.com/kubawerlos/php-cs-fixer-custom-fixers#fixers </a>what they mean and what they do.</p>

<p>Additionally the option &quot;allow-risky&quot; is now part of the php_cs.dist config. So it is not necessary anymore to call the cs-fixer with the &quot;&ndash;allow-risky&quot; parameter</p>

<h3>2019-03-01: Breaking change -&nbsp;GroupBy-Aggregations</h3>

<style type="text/css">

dl dt {
    font-weight: bolder;
    margin-top: 1rem;
}

dl dd {
    padding-left: 2rem;
}

h2 code {
    font-size: 32px;
}

.category--description ul {
    padding-left: 2rem;
}

dt code,
li code,
table code,
p code {
    font-family: monospace, monospace;
    background-color: #f9f9f9;
    font-size: 16px;
}
</style>


<p>It is now possible to group aggregations by the value of given fields. Just like GROUP BY in SQL works.</p>

<p>Every aggregation now takes a list of groupByFields as the last parameters.</p>

<p>The following Aggregation will be grouped by the category name and the manufacturer name of the product.</p>

<pre>
new AvgAggregation(&#39;product.price.gross&#39;, &#39;price_agg&#39;, &#39;product.categories.name&#39;, &#39;product.manufacturer.name&#39;)
</pre>

<p><strong>Aggregation Result</strong><br />
As aggregations can now return more than one result the `getResult()`-method returns now an array in the following form for non grouped aggregations.</p>

<pre>
[
&nbsp;&nbsp; &nbsp;[
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;key&#39; =&gt; null,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;avg&#39; =&gt; 13.33
&nbsp;&nbsp; &nbsp;]
]</pre>

<p>For grouped Aggregations it will return an array in this form:</p>

<pre>
[
&nbsp;&nbsp; &nbsp;[
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;key&#39; =&gt; [
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;product.categories.name&#39; =&gt; &#39;category1&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;product.manufacturer.name&#39; =&gt; &#39;manufacturer1&#39;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;],
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;avg&#39; =&gt; 13.33
&nbsp;&nbsp; &nbsp;],
&nbsp;&nbsp; &nbsp;[
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;key&#39; =&gt; [
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;product.categories.name&#39; =&gt; &#39;category2&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;product.manufacturer.name&#39; =&gt; &#39;manufacturer2&#39;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;],
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;avg&#39; =&gt; 33
&nbsp;&nbsp; &nbsp;]
&nbsp;&nbsp; &nbsp;
]</pre>

<p>The AggregationResult has a helper method `getResultByKey()` which returns the specific result for a given key:</p>

<pre>
$aggregationResult-&gt;getResultByKey([
&nbsp;&nbsp; &nbsp;&#39;product.categories.name&#39; =&gt; &#39;category1&#39;,
&nbsp;&nbsp; &nbsp;&#39;product.manufacturer.name&#39; =&gt; &#39;manufacturer1&#39;
]);</pre>

<p>will return:</p>

<pre>
[
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;key&#39; =&gt; [
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;product.categories.name&#39; =&gt; &#39;category1&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;product.manufacturer.name&#39; =&gt; &#39;manufacturer1&#39;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;],
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&#39;avg&#39; =&gt; 13.33
&nbsp;&nbsp; &nbsp;],</pre>

<p>The Aggregation result for the specific aggregations are deleted and just the generic AggregationResult exists.</p>

<p><strong>FIXING EXISTING AGGREGATIONS</strong><br />
As existing aggregations can&#39;t use groupBy you can simply use the first array index of the returned result:</p>

<pre>
/** @var AvgAggregationResult **/
$aggregationResult-&gt;getAverage();</pre>

<p>will become:</p>

<pre>
$aggregationResult-&gt;getResult()[0][&#39;avg&#39;];</pre>

<p>In the administration you also have to add the zero array index.</p>

<pre>
response.aggregations.orderAmount.sum;</pre>

<p>will become:</p>

<pre>
response.aggregations.orderAmount[0].sum;
</pre>

<h2>February 2019</h2>

<h3>2019-02-28: Dynamic Form Field Renderer</h3>

<p>We have a new component for dynamic rendering of form fields. This component is useful whenever you want to render forms based on external configurations or user configuration(e.g. custom fields).</p>

<p><strong>Here are some examples:</strong></p>

<pre>
* {# Datepicker #}
* &lt;sw-form-field-renderer
* &nbsp; &nbsp; &nbsp; &nbsp; type=&quot;datetime&quot;
* &nbsp; &nbsp; &nbsp; &nbsp; v-model=&quot;yourValue&quot;&gt;
* &lt;/sw-form-field-renderer&gt;
*
* {# Text field #}
* &lt;sw-form-field-renderer
* &nbsp; &nbsp; &nbsp; &nbsp; type=&quot;string&quot;
* &nbsp; &nbsp; &nbsp; &nbsp; v-model=&quot;yourValue&quot;&gt;
* &lt;/sw-form-field-renderer&gt;
*
* {# sw-colorpicker #}
* &lt;sw-form-field-renderer
* &nbsp; &nbsp; &nbsp; &nbsp; componentName=&quot;sw-colorpicker&quot;
* &nbsp; &nbsp; &nbsp; &nbsp; type=&quot;string&quot;
* &nbsp; &nbsp; &nbsp; &nbsp; v-model=&quot;yourValue&quot;&gt;
* &lt;/sw-form-field-renderer&gt;
*
* {# sw-number-field #}
* &lt;sw-form-field-renderer
* &nbsp; &nbsp; &nbsp; &nbsp; config=&quot;{
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; componentName: &#39;sw-field&#39;,
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; type: &#39;number&#39;,
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; numberType:&#39;float&#39;
* &nbsp; &nbsp; &nbsp; &nbsp; }&quot;
* &nbsp; &nbsp; &nbsp; &nbsp; v-model=&quot;yourValue&quot;&gt;
* &lt;/sw-form-field-renderer&gt;
*
* {# sw-select - multi #}
* &lt;sw-form-field-renderer
* &nbsp; &nbsp; &nbsp; &nbsp; :config=&quot;{
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; componentName: &#39;sw-select&#39;,
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; label: {
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &#39;en-GB&#39;: &#39;Multi Select&#39;
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; },
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; multi: true,
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; options: [
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; { id: &#39;option1&#39;, name: { &#39;en-GB&#39;: &#39;One&#39; } },
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; { id: &#39;option2&#39;, name: &#39;Two&#39; },
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; { id: &#39;option3&#39;, name: { &#39;en-GB&#39;: &#39;Three&#39;, &#39;de-DE&#39;: &#39;Drei&#39; } }
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ]
* &nbsp; &nbsp; &nbsp; &nbsp; }&quot;
* &nbsp; &nbsp; &nbsp; &nbsp; v-model=&quot;yourValue&quot;&gt;
* &lt;/sw-form-field-renderer&gt;
*
* {# sw-select - single #}
* &lt;sw-form-field-renderer
* &nbsp; &nbsp; &nbsp; &nbsp; :config=&quot;{
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; componentName: &#39;sw-select&#39;,
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; label: &#39;Single Select&#39;,
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; options: [
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; { id: &#39;option1&#39;, name: { &#39;en-GB&#39;: &#39;One&#39; } },
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; { id: &#39;option2&#39;, name: &#39;Two&#39; },
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; { id: &#39;option3&#39;, name: { &#39;en-GB&#39;: &#39;Three&#39;, &#39;de-DE&#39;: &#39;Drei&#39; } }
* &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ]
* &nbsp; &nbsp; &nbsp; &nbsp; }&quot;
* &nbsp; &nbsp; &nbsp; &nbsp; v-model=&quot;yourValue&quot;&gt;
* &lt;/sw-form-field-renderer&gt;</pre>

<p><strong>@description - Workflow</strong><br />
Dynamically renders components. To find out which component to render it first checks for the componentName prop. Next it checks the configuration for a componentName. If a componentName isn&#39;t specified, the type prop will be checked to automatically guess a suitable component for the type. Everything inside the config prop will be passed to the rendered child prop as properties. Also all additional props will be passed to the child.</p>

<h3>2019-02-26 : Auto configured repositories</h3>

<p>We implemented a compiler pass which configures all entity repositories automatically.</p>

<p>The compiler pass iterates all configured EntityDefinition and creates an additionally service for the EntityRepository.</p>

<p>The repository is available over the service id {entity_name}.repository.</p>

<p>If a repository is already registered with this service id, the compiler pass skips the definition</p>

<h3>2019-02-25 : Moved the sidebar component to the global page component</h3>

<p>The sidebar component is now placed in the global page component and was removed from the grid and the card-view. This makes the usage of the sidebar consistent in all pages.</p>

<p>All existing pages were updated in the same PR to match the new structure.</p>

<h3>2019-02-25 : Type-hinting for collections</h3>

<p>We recently have introduced a way to prevent mixing of class types in a collection. Now we are adding some sugar based on <a href="https://github.com/shopware/platform/pull/18">this issue </a>on github.</p>

<p>With this change, your IDE will detect the type of the collection and provides the correct type hints when used in foreach loops or when calling the add() method, etc.</p>

<p>In case you&#39;re creating a new collection class, please implement getExpectedClass() because this will be the prerequisite for automatically adding the needed doc block above the class.</p>

<pre>
&lt;?php

/**
* @method void &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;add(PluginEntity $entity)
* @method PluginEntity[] &nbsp; &nbsp;getIterator()
* @method PluginEntity[] &nbsp; &nbsp;getElements()
* @method PluginEntity|null get(string $key)
* @method PluginEntity|null first()
* @method PluginEntity|null last()
*/
class PluginCollection extends EntityCollection
{
&nbsp;&nbsp; &nbsp;protected function getExpectedClass(): string
&nbsp;&nbsp; &nbsp;{
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;return PluginEntity::class;
&nbsp;&nbsp; &nbsp;}
}</pre>

<p>If you implement a method that co-exists in the doc block, please remove the line from the doc block as it will no longer have an effect in your IDE and report it as error.</p>

<h3>2019-02-22&nbsp;: Feature: Product visibility</h3>

<p>We introduced a new Entity product_visibility which is represented by the \Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition class.</p>

<p>It allows the admin to define in which sales channel a product is visible and in which cases:</p>

<ul>
	<li>only deeplink</li>
	<li>only over search</li>
	<li>everywhere</li>
</ul>

<h3>2019-02-22&nbsp;: Administration: Loading all entities</h3>

<p>We implemented a getAll function inside the EntityStore.js class which allows to load all entities of this store. The function fetches all records of the store via queue pattern. The functionality is equals to the getList function (associations, sorting, ...).</p>

<h3>2019-02-21 : Small changes to core components</h3>

<p><strong>sw-modal</strong><br />
It is now possible to hide the header of the sw-modal.</p>

<pre>
&lt;sw-modal title=&quot;Example&quot; showHeader=&quot;false&quot;&gt;&lt;/sw-modal&gt;&nbsp;</pre>

<p><strong>sw-avatar</strong><br />
Instead of the user&#39;s initials, you can now show a placeholder avatar image (default-avatar-single).</p>

<pre>
&lt;sw-avatar placeholder&gt;&lt;/sw-avatar&gt;</pre>

<p><strong>sw-context-button</strong></p>

<p>Now you can specify an alternative icon for the context button. For example you can insert the &quot;default-action-more-vertical&quot; for the vertical three dots. To make sure the opening context menu is correctly aligned, modify the menu offset.</p>

<pre>
&lt;sw-context-button icon=&quot;default-action-more-vertical&quot; :menuOffsetLeft=&quot;18&quot;&gt;&lt;/sw-context-button&gt;</pre>

<h3>2019-02-21 : PHPUnit - random seeds gets printed</h3>

<p>We now print the seed used to randomize the order of test execution.</p>

<p>Therefor we updated PhpUnit to 8.0.4, so you may have run composer update to see the difference.</p>

<p>When the test run fails you can copy the used seed and start phpunit again with the --random-order-seed option.</p>

<p>This makes the test results reproducable and helps you debug dependencies between test cases.</p>

<h3>2019-02-21 : New &lt;sw-side-navigation&gt; component</h3>

<p>A new base component is ready for usage. It is an alternative to the tabs when the viewport width is large enough. The active page is automatically detected and visualized in the component.</p>

<p><strong>Usage:</strong></p>

<pre>
&lt;sw-side-navigation&gt;

&nbsp;&nbsp; &nbsp;&lt;sw-side-navigation-item&nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;:to=&quot;{ name: &#39;sw.link.example.page1&#39; }&quot;&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Page 1
&nbsp;&nbsp; &nbsp;&lt;/sw-side-navigation-item&gt;
&nbsp;&nbsp; &nbsp;
&nbsp;&nbsp; &nbsp;&lt;sw-side-navigation-item&nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;:to=&quot;{ name: &#39;sw.link.example.page2&#39; }&quot;&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Page 2
&nbsp;&nbsp; &nbsp;&lt;/sw-side-navigation-item&gt;
&nbsp;&nbsp; &nbsp;
&nbsp;&nbsp; &nbsp;&lt;sw-side-navigation-item&nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;:to=&quot;{ name: &#39;sw.link.example.page3&#39; }&quot;&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Page 3
&nbsp;&nbsp; &nbsp;&lt;/sw-side-navigation-item&gt;
&nbsp;&nbsp; &nbsp;
&nbsp;&nbsp; &nbsp;&lt;sw-side-navigation-item&nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;:to=&quot;{ name: &#39;sw.link.example.page4&#39; }&quot;&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Page 4
&nbsp;&nbsp; &nbsp;&lt;/sw-side-navigation-item&gt; &nbsp;
&nbsp;&nbsp; &nbsp;
&lt;/sw-side-navigation&gt;</pre>

<p>The &lt;sw-side-navigation-item&gt; works exactly like a router link and can receive the same props.</p>

<p>Important: In the future the component will be combined with the new tabs component which has the same styling. It will be a switchable component with a horizontal and vertical mode.</p>

<h3>2019-02-20 : ESLint disabled by default</h3>

<p>ESLint in the Hot Module Reload mode is now disabled by default. You can re-enable it in your psh.yaml file with ESLINT_DISABLE: &quot;true&quot;.</p>

<p>To keep our rules still applied, we&#39;ve added eslint with &ndash;-fix to our pre-commit hook like we do with PHP files. If there are changes, that cannot be safely fixed by eslint, it will show you an error log. Please fix the shown issues and try to commit again.</p>

<p>In addition, both code styles in PHP and JS will be checked in our CI environment, so don&#39;t try to commit with &ndash;-no-verify.</p>

<h3>2019-02-19 : Configuration changes to sw-datepicker</h3>

<p>In order to prevent conflicts between the type properties of sw-field and sw-datepicker we replaced sw-datepicker&#39;s type with a new property dateType. The new property works similar to the old type property of the datepicker.</p>

<pre>
&lt;sw-field type=&quot;date&quot; dateType=&quot;datetime&quot; ...&gt;&lt;/sw-field&gt;</pre>

<p>Valid values for dateType are:</p>

<ul>
	<li>time</li>
	<li>date</li>
	<li>datetime</li>
	<li>datetime-local</li>
</ul>

<h3>2019-02-18 : New OneToOneAssociationField</h3>

<p>The new OneToOneAssociationField allows to register a 1:1 relation in the DAL.</p>

<p>This is especially important for plugin developers to extend existing entities where the values are stored in separate columns in the database.</p>

<p>Important for the 1:1 relation is to set the RestrictDelete and CascadeDelete.</p>

<p>Furthermore, the DAL always assumes a bi-directional association, so the association must be defined on both sides. Here is an example where a plugin adds another relation to the ProductDefinition:</p>

<pre>
ProductDefinition.php

protected static function defineFields(): FieldCollection
{
&nbsp; &nbsp; return new FieldCollection([
&nbsp;&nbsp; &nbsp; &nbsp; //...
&nbsp;&nbsp; &nbsp; &nbsp; (new OneToOneAssociationField(
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &#39;pluginEntity&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &#39;id&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &#39;product_id&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; PluginEntityDefinition::class,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; false)
&nbsp;&nbsp; &nbsp; &nbsp; )-&gt;addFlags(new CascadeDelete())
]);

</pre>

<p>&nbsp;</p>

<pre>
PluginEntityDefinition.php

protected static function defineFields(): FieldCollection
{
&nbsp; &nbsp; return new FieldCollection([
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;//...
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;(new OneToOneAssociationField(
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&#39;product&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&#39;product_id&#39;, &nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&#39;id&#39;,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;ProductDefinition::class,
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;false)
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;)-&gt;addFlags(new RestrictDelete())
&nbsp; &nbsp; ]);</pre>

<p>&nbsp;</p>

<h3>2019-02-12 : PHPUnit 8 + PCOV</h3>

<p>In order to get faster CodeCoverage we updated to PHPUnit 8 and installed PCOV on the Docker app container. So please rebuild your container and do a composer install.</p>

<p><strong>PHPUnit 8</strong><br />
Sadly PHPUnit 8 comes with BC-Changes that may necessitate changes in your open PRs. The most important ones:</p>

<ul>
	<li>setUp and tearDown now require you to add a void return typehint</li>
	<li>assertArraySubset is now deprecated. Please no longer use it</li>
</ul>

<p>For a full list of changes please see the official announcement:<a href="http://https://phpunit.de/announcements/phpunit-8.html"> https://phpunit.de/announcements/phpunit-8.html</a></p>

<p><strong>PCOV</strong><br />
The real reason for this change! PCOV generates CodeCoverage <strong>in under 4 minutes</strong> on a docker setup.</p>

<p>If you want to generate coverage inside of your container you need to enable pcov through a temporary ini setting first. As an example this will write coverage information to /coverage:</p>

<pre>
php -d pcov.enabled=1 vendor/bin/phpunit --configuration vendor/shopware/platform/phpunit.xml.dist --coverage-html coverage</pre>

<p>you are developing directly on your machine please take a look at <a href="https://github.com/krakjoe/pcov/blob/develop/INSTALL.md">https://github.com/krakjoe/pcov/blob/develop/INSTALL.md</a> for installation options.</p>

<h3>2019-02-11 :Refactoring of &lt;sw-field&gt; and new url field for ssl switching</h3>

<p><strong>&lt;SW-FIELD&gt; REFACTORING</strong><br />
The sw-field was refactored for simpler usage of suffix, prefix and tooltips. You can use it now simply as props. The suffix and prefix is also slotable for more advanced solutions.</p>

<p>Usage example:</p>

<pre>
&lt;sw-field&nbsp;
&nbsp;&nbsp; &nbsp;type=&quot;text&quot;
&nbsp;&nbsp; &nbsp;label=&quot;Text field:&quot;
&nbsp;&nbsp; &nbsp;placeholder=&quot;Placeholder text&hellip;&quot;
&nbsp;&nbsp; &nbsp;prefix=&quot;Prefix&quot;
&nbsp;&nbsp; &nbsp;suffix=&quot;Suffix&quot;
&nbsp;&nbsp; &nbsp;:copyAble=&quot;false&quot;
&nbsp;&nbsp; &nbsp;tooltipText=&quot;I am a tooltip!&quot;
&nbsp;&nbsp; &nbsp;tooltipPosition=&quot;bottom&quot;
&nbsp;&nbsp; &nbsp;helpText=&quot;This is a help text.&quot;
&gt;
&lt;/sw-field&gt;</pre>

<p><strong>NEW FIELD: &lt;SW-FIELD TYPE=&quot;URL&quot;&gt;</strong><br />
Another news is the new SSL-switch field. It allows the user to type or paste a url and the field shows directly if its a secure or unsecure http connection. The user can also change the url with a switch from a secure to an unsecure connection or the other way around.</p>

<p>The field is extended from the normal sw-field. Hence it also allows to use prefix, tooltips, &hellip;</p>

<p>Usage example:</p>

<pre>
&lt;sw-field&nbsp;
&nbsp;&nbsp; &nbsp;type=&quot;url&quot;
&nbsp;&nbsp; &nbsp;v-model=&quot;theNeededUrl&quot;
&nbsp;&nbsp; &nbsp;label=&quot;URL field:&quot;
&nbsp;&nbsp; &nbsp;placeholder=&quot;Type or paste an url&hellip;&quot;
&nbsp;&nbsp; &nbsp;switchLabel=&quot;The description for the switch field&quot;&gt;
&lt;/sw-field&gt;</pre>

<h3>2019-02-08: &lt;sw-tree&gt; refactoring</h3>

<p>The sw-tree now has a function prop createFirstItem whicht will be calles when there are no items in the tree. This should be used to create an initial item if none are given. All other items shoud be created via functions from the action buttons on each item. e.g.: addCategoryBefore or addCategoryAfter. You&#39;ll have to create these functions for the given case and override the slot actions of the sw-tree-item.</p>

<h3>2019-02-07: Rule documentation</h3>

<p>The rules documentation is now available. You are now able to read how to create your own rules using the shopware/platform! Any feedback is appreciated.</p>

<p>See it at: <a href="https://github.com/shopware/platform/blob/master/src/Docs/60-plugin-system/35-custom-rules.md">https://github.com/shopware/platform/blob/master/src/Docs/60-plugin-system/35-custom-rules.md</a></p>

<h3>2019-02-07: System requirements</h3>

<p>The platform now requires PHP &gt;= 7.2.0. We&#39;ve also included a polyfill library for PHP 7.3 functions, so feel free to use them.</p>

<h3>2019-02-06: Plugin configuration</h3>

<p>It is now possible for plugins to create a configuration. This configuration gets dynamically rendered in the administration, however this feature is not actively used right now. Add a new Resources/config.xml to your plugin. Take a look at this short example:</p>

<pre>
&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt;
&lt;config xmlns:xsi=&quot;http://www.w3.org/2001/XMLSchema-instance&quot;
xsi:noNamespaceSchemaLocation=&quot;https://raw.githubusercontent.com/shopware/platform/master/src/Core/System/SystemConfig/Schema/config.xsd&quot;&gt;

&nbsp;&nbsp; &nbsp;&lt;card&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;title&gt;Basic Configuration&lt;/title&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;title lang=&quot;de_DE&quot;&gt;Grundeinstellungen&lt;/title&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;input-field type=&quot;password&quot;&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;name&gt;secret&lt;/name&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;label&gt;Secret token&lt;/label&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;label lang=&quot;de_DE&quot;&gt;Geheim Schl&uuml;ssel&lt;/label&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;helpText&gt;Your secret token for xyz...&lt;/helpText&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;helpText lang=&quot;de_DE&quot;&gt;Dein geheimer Schl&uuml;ssel&lt;/helpText&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;/input-field&gt;
&nbsp;&nbsp; &nbsp;&lt;/card&gt;
&lt;/config&gt;</pre>

<p>The configuration is completely optional and meant to help people create a configuration page for their plugin without requiring any knowledge of templating or the Shopware Administration. Read more about the plugin configuration here.</p>

<h3>2019-02-07: New project setup</h3>

<p>Hey folks, currently, the project setup steps are</p>

<ul>
	<li>checkout shopware/development</li>
	<li>run composer install</li>
	<li>change directory to vendor/shopware/platform</li>
	<li>setup stash as remote</li>
	<li>setup PHPStorm to allow editing files in the vendor folder</li>
</ul>

<p><strong>PROBLEMS</strong><br />
One of the big problems is, that if a new dependency is required to be installed, you may break your current project setup as you&#39;ll loose your history in vendor/shopware/platform because composer will detect changes and restores it to a new checkout from github. You have to do the setup all over again.</p>

<p>And to be honest, the setup process isn&#39;t straightforward.</p>

<p><strong>New project setup</strong><br />
From now on, you don&#39;t have to work in vendor/shopware/platform to make changes. In order to use the new process, follow these instructions:</p>

<ul>
	<li>clone shopware/development</li>
	<li>clone shopware/platform in folder platform in the development root directory</li>
	<li>run composer install</li>
</ul>

<p><strong>WHAT HAS CHANGED?</strong><br />
If the platform directory exists, composer will use it as source for the shopware/platform dependency and symlinks it into vendor/shopware/platform. In PHPStorm, you&#39;ll always work in ./platform for your platform changes. They will be automatically be synced to the vendor directory - because it&#39;s a symlink. :zwinkern: This change will also speed up the CI build time significantly.</p>

<p><strong>UPGRADE FROM CURRENT SETUP</strong><br />
To make sure you can use the new setup:</p>

<ul>
	<li>save your current work (push) in vendor/shopware/platform</li>
	<li>clone shopware/platform into in the development root directory as platform</li>
	<li>remove vendor/ and composer.lock</li>
	<li>run composer install</li>
	<li>
	<h3>2019-02-05: Plugin changelogs</h3>
	</li>
	<li>Changelogs could now be provided by plugins:<br />
	Add a new `CHANGELOG.md` file in the root plugin directory. The content has to look like this:</li>
	<li>
	<pre>
# 1.0.0
- initialized SwagTest
* refactored composer.json

# 1.0.1
- added migrations
* done nothing</pre>
	</li>
	<li>If you want to provide translated changelogs, create a `CHANGELOG-de_DE.md`<br />
	The changelog is optional</li>
	<li>
	<h3>2019-02-04: Sample payment plugin available</h3>
	</li>
	<li>A first prototype of a payment plugin is now available on github <a href="https://github.com/shopwareLabs/SwagPayPal">https://github.com/shopwareLabs/SwagPayPal</a></li>
</ul>

<p>&nbsp;</p>

<h3>2019-02-04: sw-field refactoring</h3>

<p>The sw-field is now a functional component which renders the single compontents based on the supplied type. There are no changes in the behavior.</p>

<p>It is now possible to pass options to the select-type which will be rendered as the options with option.id as the value and option.name as the option-name. If you want to use the select as before with slotted options you now don&#39;t need to set slot=&quot;options&quot; because the options will now be passed via the default slot.</p>

<p>All input-types are now available as single components.</p>

<p>sw-text-field for &lt;input type=&quot;text&quot;</p>

<p>sw-password-field for type=&quot;password&quot;</p>

<p>sw-checkbox-field for type=&quot;checkbox&quot;</p>

<p>sw-colorpicker for a colorpicker</p>

<p>sw-datepicker for a datepicker. Here you can pass type time, date, datetime and datetime-local to get the desired picker.</p>

<p>sw-number-field for an input which supports numberType int and float and the common type=&quot;number&quot; params lik step, min and max.</p>

<p>sw-radio-field for type=&quot;radio&quot; with options for each radio-button where option.value is the value and option.name is the label for each field</p>

<p>sw-select-field for &lt;select&gt; where the usage is as described above.</p>

<p>sw-switch-field for type=&quot;checkbox&quot; with knob-styling (iOS like).</p>

<p>sw-textarea-field for &lt;textarea&gt;</p>

<p>sw-field should be used preferably. Single components should be used to save perforamnce in given cases.</p>

<h3>2019-02-01: Storefront building pipeline</h3>

<p>The Shopware platform&nbsp;Storefront Building Pipline provides the developer with the ability to use a Node.js based tech stack to build the storefront.</p>

<p><strong>This has many advantages:</strong></p>

<ul>
	<li>Super fast building and rebuilding speed</li>
	<li>Hot Module Replacement</li>
	<li>Automatic polyfill detection based on a browser list</li>
	<li>Additional CSS processing for example automatic generation of vendor prefixes</li>
	<li>The building pipeline is based on Webpack including a dozen plugins. In the following we&#39;re talking a closer look on the pipeline:</li>
</ul>

<p><strong>JS Compilation</strong></p>

<ul>
	<li>babel 7 including babel/preset-env for the ES6-to-ES5 transpilation</li>
	<li>eslint including eslint-recommended rule set for JavaScript linting</li>
	<li>terser-webpack-plugin for the minification</li>
	<li>&nbsp;</li>
	<li><strong>CSS Compilation</strong></li>
	<li><br />
	sass-loader as SASS compiler<br />
	postcss-loader for additional CSS processing<br />
	autoprefixer for the automatic generation of vendor prefixes<br />
	pxtorem for automatic transformation from pixel to rem value<br />
	stylelint for SCSS styles linting based on stylelint-config-sass-guidelines</li>
</ul>

<p><strong>Hot Module Replacement Server</strong></p>

<ul>
	<li>based on Webpack&#39;s devServer</li>
	<li>Overlay showing compilation as well as linting errors right in the browser</li>
</ul>

<p><strong>Additional tooling</strong></p>

<p>friendly-errors-webpack-plugin for a clean console output while using the Hot Module Replacement Server</p>

<ul>
	<li>webpack-bundle-analyzer for analyizing the bundle structure and finding huge packages which are impacting the client performance</li>
	<li>&nbsp;</li>
</ul>

<p><strong>Installation</strong><br />
All commands which are necessary for storefront development can be accessed from the root directory of your shopware instance. The storefront commands are prefixed with storefront:</p>

<pre>
./psh.phar storefront:{COMMAND}</pre>

<p><a href="https://github.com/shopwareLabs/psh">Find out more about about PSH.</a></p>

<p><strong>INSTALL DEPENDENCIES</strong><br />
To get going you first need to install the development dependencies with the init command:</p>

<pre>
./psh.phar storefront:install</pre>

<p>This will install all necessary dependencies for your local environment using <a href="https://www.npmjs.com/">NPM</a>.</p>

<p><strong>Development vs. production build</strong></p>

<p>The development build provides you with an uncompressed version with source maps. The production build on the other hand minifies the JavaScript, combines it into a single file as well as compresses the CSS and combines it into a single file.</p>

<p>The linting of JavaScript and SCSS files is running in both variants.</p>

<p><strong>DEVELOPMENT BUILD</strong></p>

<pre>
./psh.phar storefront:dev</pre>

<p><strong>PRODUCTION BUILD</strong></p>

<pre>
./psh.phar storefront:prod
</pre>

<p><strong>Hot module replacement</strong></p>

<p>The hot module replacement server is a separate node.js server which will be spawned and provides an additional websocket endpoint which pushes updates right to the client. Therefore you don&#39;t have to refresh the browser anymore.</p>

<pre>
./psh.phar storefront:watch</pre>

<h2>January 2019</h2>

<h3>2019-01-31: Roadmap update</h3>

<p>Here you will find a current overview of the epics that are currently being implemented, which have been completed and which will be implemented next.</p>

<p><strong>Open</strong><br />
Work on these Epics has not yet begun.</p>

<ul>
	<li>Theme Manager</li>
	<li>Tags</li>
	<li>Product Export</li>
	<li>First Run Wizard</li>
	<li>Backend Search</li>
	<li>Caching</li>
	<li>Sales Channel</li>
	<li>Additional Basket Features</li>
	<li>Shipping / Payment</li>
	<li>Import / Export</li>
	<li>Mail Templates</li>
	<li>Installer / Updater</li>
	<li>SEO Basics</li>
	<li>Newsletter Integeration</li>
</ul>

<p><strong>Next</strong><br />
These epics are planned as the very next one</p>

<ul>
	<li>Documents</li>
	<li>Custom Fields</li>
	<li>Plugin Manager</li>
	<li>Customer</li>
	<li>Core Settings</li>
	<li>&nbsp;</li>
	<li><strong>In Progress</strong></li>
</ul>

<p>These Epics are in the implementation phase</p>

<ul>
	<li>Products</li>
	<li>Variants / Properties</li>
	<li>SalesChannel API / Page, Pagelets</li>
	<li>Order</li>
	<li>CMS</li>
	<li>Categories</li>
	<li>Product Streams</li>
	<li>ACL</li>
	<li>Background processes</li>
</ul>

<p><strong>Review</strong><br />
All Epics listed here are in the final implementation phase and will be reviewed again.</p>

<ul>
	<li>Rule Builder</li>
	<li>Plugin System</li>
	<li>Snippets</li>
</ul>

<p><strong>Done</strong><br />
These epics are finished</p>

<ul>
	<li>Media Manager</li>
	<li>Content Translations</li>
	<li>Supplier</li>
</ul>

<h3>2019-01-29: LESS becomes SAAS</h3>

<p>We changed the core styling of the shopware administration from LESS to SASS/SCSS. We did that because the shopware storefront will also have SCSS styling with Bootstrap in the future and we wanted to have a similar code style.</p>

<p><strong>Do we use the SASS or SCSS syntax?</strong><br />
We use SCSS! When it comes to brackets and indentations everything stays the same. For comparison: <a href="https://sass-lang.com/guide">https://sass-lang.com/guide </a>(You can see a syntax switcher inside the code examples)</p>

<p><strong>What if my whole module or plugin is still using LESS?</strong><br />
This should have no effect in the first place because SCSS is only an addition. All Vue components do support both LESS and SCSS. All LESS variables and mixins are still available for the moment in order to prevent plugins from breaking. When all plugins are migrated to SCSS styles we can get rid of the LESS variables and mixins.</p>

<p><strong>How do I change my LESS to SCSS?</strong></p>

<ul>
	<li><strong>Run administration:init</strong>

	<ul>
		<li>The new SASS has to be installed first.</li>
	</ul>
	</li>
	<li><strong>Change file extension from .less to .scss</strong>
	<ul>
		<li>Please beware of the import inside the index.js file.</li>
	</ul>
	</li>
	<li><strong>Change the alias inside the style imports:</strong>
	<ul>
		<li>The alias inside the style imports changes from ~less to ~scss:</li>
	</ul>
	</li>
	<li>
	<pre>
// Old
@import &#39;~less/variables&#39;;

// New
@import &#39;~scss/variables&#39;;</pre>

	<ul>
		<li><strong>Change variable prefixes:</strong><br />
		Variable prefixes has to be changed from @ to $:</li>
		<li>
		<pre>
// Old
color: @color-shopware;

// New
color: $color-shopware;</pre>
		</li>
		<li>
		<p>If you do a replace inside your IDE, please take care of the Style Imports as well as the MediaQueries.</p>

		<p>All base variables have been migrated to SCSS and can be used as before.</p>
		</li>
		<li>
		<p><strong>Change mixin calls:</strong></p>
		</li>
		<li>
		<pre>
// Old
.truncate();

// New
@include truncate();</pre>
		</li>
	</ul>
	</li>
</ul>

<h3>2019-01-29: Clone entities</h3>

<p>It is now possible to clone entities in the system via the following endpoint:</p>

<p>/api/v1/_action/clone/{entity}/{id}</p>

<p>As a response you will get the new id</p>

<p>{ id: &quot;a3ad........................................ }</p>

<p><strong>What will be cloned with the entity?</strong></p>

<ul>
	<li>OneToMany associations marked with CascadeDelete flag</li>
	<li>ManyToMany associations (here only the mapping tables entries)</li>
	<li>For example product N:M category (mapping: product_category)</li>
</ul>

<p>The category entities are not cloned with product_category entries are cloned</p>

<h3>2019-01-29: Object cache</h3>

<p>The cache can be referenced at shopware.cache. Here you find a \Symfony\Component\Cache\Adapter\TagAwareAdapterInterface behind. This allows additional tags to be stored on a CacheItem:</p>

<pre>
$item = $this-&gt;cache-&gt;getItem(&#39;test&#39;);
$item-&gt;tag([&#39;a&#39;, &#39;b&#39;];
$item-&gt;set(&#39;test&#39;)
$this-&gt;cache-&gt;save($item);</pre>

<p>What do we use the cache for?</p>

<ul>
	<li>Caching Entities</li>
	<li>Caching from Entity Searches</li>
</ul>

<p>Where is the caching located in the core?</p>

<pre>
\Shopware\Core\Framework\DataAbstractionLayer\Cache\CachedEntityReader
\Shopware\Core\Framework\DataAbstractionLayer\Cache\CachedEntitySearcher</pre>

<p>When do I have to consider the cache?</p>

<ul>
	<li>In all indexers, i.e. whenever you write directly to the database</li>
</ul>

<p>Here you can find an example \Shopware\Core\Content\Rule\DataAbstractionLayer\Indexing\RulePayloadIndexer</p>

<h3>2019-01-29: sw-button new features</h3>

<p>The sw-button component has been extended by some smaller features.</p>

<ul>
	<li>Square Button (For buttons which only contain an icon)</li>
	<li>Button Group (Shows buttons in a &quot;button bar&quot; without spacing in between)</li>
	<li>Split Button (A combination of Button Group, Square Button and Context Menu)</li>
</ul>

<p><img alt="" src="https://sbp-testingmachine.s3.eu-west-1.amazonaws.com/1552462731/slack-imgs.png" />Here are some code examples which show you how to use the new features:</p>

<pre>
&lt;!-- Square buttons --&gt;
&lt;sw-button square size=&quot;small&quot;&gt;
&nbsp;&nbsp; &nbsp;&lt;sw-icon name=&quot;small-default-x-line-medium&quot; size=&quot;16&quot;&gt;&lt;/sw-icon&gt;
&lt;/sw-button&gt;
&lt;sw-button square size=&quot;small&quot; variant=&quot;primary&quot;&gt;
&nbsp;&nbsp; &nbsp;&lt;sw-icon name=&quot;small-default-checkmark-line-medium&quot; size=&quot;16&quot;&gt;&lt;/sw-icon&gt;
&lt;/sw-button&gt;

&lt;!-- Default button group --&gt;
&lt;sw-button-group splitButton&gt;
&nbsp;&nbsp; &nbsp;&lt;sw-button-group&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;sw-button&gt;Button 1&lt;/sw-button&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;sw-button&gt;Button 2&lt;/sw-button&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;sw-button&gt;Button 3&lt;/sw-button&gt;
&nbsp;&nbsp; &nbsp;&lt;/sw-button-group&gt;
&lt;/sw-button-group&gt;

&lt;!-- Primary split button with context menu --&gt;
&lt;sw-button-group splitButton&gt;
&nbsp;&nbsp; &nbsp;&lt;sw-button variant=&quot;primary&quot;&gt;Save&lt;/sw-button&gt;
&nbsp;&nbsp; &nbsp;
&nbsp;&nbsp; &nbsp;&lt;sw-context-button&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;sw-button square slot=&quot;button&quot; variant=&quot;primary&quot;&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;sw-icon name=&quot;small-arrow-medium-down&quot; size=&quot;16&quot;&gt;&lt;/sw-icon&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;/sw-button&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;sw-context-menu-item&gt;Save and exit&lt;/sw-context-menu-item&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;sw-context-menu-item&gt;Save and publish&lt;/sw-context-menu-item&gt;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&lt;sw-context-menu-item variant=&quot;danger&quot;&gt;Delete&lt;/sw-context-menu-item&gt;
&nbsp;&nbsp; &nbsp;&lt;/sw-context-button&gt;
&lt;/sw-button-group&gt;</pre>

<h3>2019-01-29: Automatic generation of api services based on entity scheme</h3>

<p>We changed the handling of API services. The services are now generated automatically based on the entity scheme.</p>

<p>It&#39;s still possible to create custom API serivces. To do so, as usual, create a new file in the directory src/core/service/api. You don&#39;t have to deal with the registration of these services - the administration will automatically import and register the service into the application for you.</p>

<p>Something has changed though - the base API serivce is located under src/core/service instead of src/core/service/api.</p>

<p>There&#39;s something you have to keep in mind tho. Please switch the pfads accordingly and custom API services are needing a name property which represents the name the application uses to register the service.</p>

<p>Here&#39;s an example CustomerAddressApiService:</p>

<pre>
// Changed import path
import ApiService from &#39;../api.service&#39;;

/**
&nbsp;* Gateway for the API end point &quot;customer_address&quot;
&nbsp;* @class
&nbsp;* @extends ApiService
&nbsp;*/
class CustomerAddressApiService extends ApiService {
&nbsp; &nbsp; constructor(httpClient, loginService, apiEndpoint = &#39;customer_address&#39;) {
&nbsp; &nbsp; &nbsp; &nbsp; super(httpClient, loginService, apiEndpoint);
&nbsp; &nbsp;&nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; // Name of the service
&nbsp; &nbsp; &nbsp; &nbsp; this.name = &#39;customerAddressService&#39;;
&nbsp; &nbsp; }

&nbsp; &nbsp; // ...
}</pre>

<p>&nbsp;</p>

<h3>2019-01-29: Feature flags</h3>

<p>In the shopware platform you can switch off features via environment variables and also merge &quot;Work in Progress&quot; changes into the master. So how does this work?</p>

<p><strong>Create</strong><br />
When you start developing a new feature, you should first create a new flag. As a convention we use a Jira reference number here. Remember, this will be published to GitHub, so just take the issue number.</p>

<pre>
bin/console feature:add NEXT-1128
</pre>

<p><strong>Creates</strong></p>

<pre>
application@d162c25ff86e:/app$ bin/console feature:add NEXT-1128

Creating feature flag: NEXT-1128
==============================================

&nbsp;---------- --------------------------------------------------------------------------------------------------------------&nbsp;
&nbsp; Type &nbsp; &nbsp; &nbsp; Value &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;
&nbsp;---------- --------------------------------------------------------------------------------------------------------------&nbsp;
&nbsp; PHP-Flag &nbsp; /app/components/platform/src/Core/Flag/feature_next1128.php &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; JS-Flag &nbsp; &nbsp;/app/components/platform/src/Administration/Resources/administration/src/flag/feature_next1128.js &nbsp;
&nbsp; Constant &nbsp; FEATURE_NEXT_1128 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;
&nbsp;---------- --------------------------------------------------------------------------------------------------------------&nbsp;

&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;
&nbsp;[OK] Created flag: NEXT-1128 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;

&nbsp;! [NOTE] Please remember to add and commit the files&nbsp;</pre>

<p>After that you should make a git add to add the new files.</p>

<p><strong>Enable</strong><br />
The system disables all flags per default. To switch the flags on you can simply add them to your .psh.yaml.override. An example might look like this:</p>

<pre>
const:
&nbsp; FEATURES: |
&nbsp; &nbsp; FEATURE_NEXT_1128=1</pre>

<p>This is automatically written to the .env file and from there imported into the platform.</p>

<p><strong>USAGE IN PHP</strong><br />
The interception points in the order of their usefulness:</p>

<pre>
&lt;service ...&gt;
&nbsp; &nbsp;&lt;tag name=&quot;shopware.feature&quot; flag=&quot;next1128&quot;/&gt;
&lt;/service&gt;</pre>

<p>If possible, you should be able to toggle your additional functionality over the DI container. The service exists only if the flag is enabled.</p>

<p>Everything else is implemented in the form of PHP functions. These are created through the feature:add command.</p>

<pre>
use function Flag\skipTestNext1128NewDalField;

class ProductTest
{
&nbsp; public function testNewFeature()&nbsp;
&nbsp; {
&nbsp; &nbsp; &nbsp;skipTestNext1128NewDalField($this);

&nbsp; &nbsp; &nbsp;// test code
&nbsp; }
}</pre>

<p>If you customize a test, you can flag it by simply calling this function. Also works in setUp</p>

<p>If there is no interception point through the container, you can use other functions:</p>

<pre>
use function Flag\ifNext1128NewDalFieldCall;
class ApiController
{

&nbsp; public function indexAction(Request $request)
&nbsp; {
&nbsp; &nbsp; // some old stuff
&nbsp; &nbsp; ifNext1128NewDalFieldCall($this, &#39;handleNewFeature&#39;, $request);
&nbsp; &nbsp; // some old stuff
&nbsp; }

&nbsp; private function handleNewFeature(Request $request)
&nbsp; {
&nbsp; &nbsp; // awesome new stuff
&nbsp; }
}</pre>

<p>Just create your own, private, method in which you do the new stuff, nobody can mess with you!</p>

<pre>
use function Flag\ifNext1128NewDalField;
class ApiController
{

&nbsp; public function indexAction(Request $request)
&nbsp; {
&nbsp; &nbsp; // some old stuff
&nbsp; &nbsp; ifNext1128NewDalField(function() use ($request) {
&nbsp; &nbsp; &nbsp; // awesome stuff
&nbsp; &nbsp; });
&nbsp; &nbsp; // some old stuff
&nbsp; }

}</pre>

<p>If this seems like &#39;too much&#39; to you, use a callback to connect your new function. Also here it will be hard for others to mess you up.</p>

<pre>
use function Flag\next1128NewDalField;
class ApiController
{
&nbsp; public function indexAction(Request $request)
&nbsp; {
&nbsp; &nbsp; // some old stuff
&nbsp; &nbsp; if (next1128NewDalField()) {
&nbsp; &nbsp; &nbsp; //awesome new stuff
&nbsp; &nbsp; }
&nbsp; &nbsp; // some old stuff
&nbsp; }
}</pre>

<p>If there is really no other way, there is also a simple function that returns a Boool. Should really only happen in an emergency, because you&#39;re in the same scope as everyone else. So other flags can easily overwrite your variables.</p>

<p><strong>USAGE IN THE ADMIN SPA</strong><br />
This works very similar to the PHP hook points. The preferred interception points are only slightly different though</p>

<pre>
&lt;sw-field type=&quot;text&quot;
&nbsp;&nbsp; &nbsp;...
&nbsp;&nbsp; &nbsp;v-if=&quot;next1128NewDalField&quot;
&nbsp;&nbsp; &nbsp;...&gt;
&lt;/sw-field&gt;</pre>

<p>To simply hide an element, use v-if with the name of your flag. This is always registererd and defaults to false. In this case the whole component will not even be instantiated.</p>

<pre>
import { NEXT1128NEWDALFIELD } from &#39;src/flag/feature_next1128NewDalField&#39;;

Module.register(&#39;sw-awesome&#39;, {
&nbsp;&nbsp; &nbsp;flag: NEXT1128NEWDALFIELD,
&nbsp;&nbsp; &nbsp;...
});</pre>

<p>With this you can remove a whole module from the administration pannel.</p>

<p>If these intervention points are not sufficient the functions from PHP are also available - almost 1:1.</p>

<pre>
import {
&nbsp; &nbsp;ifNext1128NewDalField,
&nbsp; &nbsp;ifNext1128NewDalFieldCall,
&nbsp; &nbsp;next1128NewDalField
} from &quot;src/flag/feature_next1128NewDalField&quot;;

ifNext1128NewDalFieldCall(this, &#39;changeEverything&#39;);

ifNext1128NewDalField(() =&gt; {
&nbsp; &nbsp;// something awesome
});

if (next1128NewDalField) {
&nbsp; &nbsp;// something awesome
}</pre>

<p>These can also be used freely in the components. However, the warnings from the PHP part also apply here!</p>

<h3>2019-01-29: Symfony service naming</h3>

<p>Until now, we have always used the following format for service definitions:</p>

<p>&lt;service class=&quot;Shopware\Core\Checkout\Cart\Storefront\CartService&quot; id=&quot;Shopware\Core\Checkout\Cart\Storefront\CartService&quot;/&gt;</p>

<p>The reason for this was that PHPStorm could only resolve the class and the ID was not recognized as a class. Therefore we maintained the two parameters. This is no longer a problem. Therefore we changed the platform repo and development template. The new format now looks like this:</p>

<p>&lt;service id=&quot;Shopware\Core\Checkout\Cart\Storefront\CartService&quot;/&gt;</p>

<p>There is also a test which enforces the new format.</p>

<h3>2019-01-29: Entity changes</h3>

<p>Each entity now has the getUniqueIdentifier and setUniqueIdentifier methods necessary for the DAL. The uniqueIdentfier is the first step to support multi column primary keys.</p>

<p>The getId/setId and Property $id methods are no longer implemented by default, but can be easily added with the EntityIdTrait. This default implementation automatically sets the uniqueIdentifier, which has to be set for a manual implementation.</p>

<h3>2019-01-29: System language</h3>

<p>There is now a system language that serves as the last fallback. At the moment it is still hardcoded en_GB. This should be configurable in the future. Important: If you create new entities, you must always provide a translation for Defaults::LANGUAGE_SYSTEM.</p>

<p>The constant Defaults::LANGUAGE_SYSTEM replaces Defaults::LANGUAGE_EN, which is now deprecated. Please exchange this everywhere. Since there can be a longer translation chain now, it is now also stored as an array in the context. Context::getFallbackLanguageId was removed, instead there is Context::getLanguageIdChain.</p>

<h3>2019-01-29: EntityTranslationDefinition simplification</h3>

<p>Changed defineFields to only define the translated fields. Primary key, Foreign key und die standard associations are determined automatically. But EntityTranslationDefinition::getParentDefinitionClass (previously called getRootEntity) is no longer optional.</p>

<p><strong>Before</strong>:</p>

<pre>
class OrderStateTranslationDefinition extends EntityTranslationDefinition
{
&nbsp;&nbsp; &nbsp;public static function defineFields(): FieldCollection
&nbsp;&nbsp; &nbsp;{
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;return new FieldCollection([
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;(new FkField(&#39;order_state_id&#39;, &#39;orderStateId&#39;, OrderStateDefinition::class))-&gt;setFlags(new PrimaryKey(), new Required()),
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;(new ReferenceVersionField(OrderStateDefinition::class))-&gt;setFlags(new PrimaryKey(), new Required()),
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;(new FkField(&#39;language_id&#39;, &#39;languageId&#39;, LanguageDefinition::class))-&gt;setFlags(new PrimaryKey(), new Required()),
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;(new StringField(&#39;description&#39;, &#39;description&#39;))-&gt;setFlags(new Required()),
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;new CreatedAtField(),
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;new UpdatedAtField(),
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;new ManyToOneAssociationField(&#39;orderState&#39;, &#39;order_state_id&#39;, OrderStateDefinition::class, false),
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;new ManyToOneAssociationField(&#39;language&#39;, &#39;language_id&#39;, LanguageDefinition::class, false),
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;]);
&nbsp;&nbsp; &nbsp;}
&nbsp;&nbsp; &nbsp;public static function getRootEntity(): ?string
&nbsp;&nbsp; &nbsp;{
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;return OrderStateDefinition::class;
&nbsp;&nbsp; &nbsp;}
}</pre>

<p><strong>After</strong>:</p>

<pre>
class OrderStateTranslationDefinition extends EntityTranslationDefinition
{
&nbsp;&nbsp; &nbsp;public static function getParentDefinitionClass(): string
&nbsp;&nbsp; &nbsp;{
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;return OrderStateDefinition::class;
&nbsp;&nbsp; &nbsp;}
&nbsp;&nbsp; &nbsp;protected static function defineFields(): FieldCollection
&nbsp;&nbsp; &nbsp;{
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;return new FieldCollection([
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;(new StringField(&#39;description&#39;, &#39;description&#39;))-&gt;setFlags(new Required()),
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;]);
&nbsp;&nbsp; &nbsp;}
}</pre>

<p>In addition, all defineFields methods have been set to protected, since they should only be called internally.</p>

<h3>2019-01-29: Collection Classes</h3>

<p>The collection classes have been cleaned up and a potential bug has been resolved. Since there are no generics in PHP, you can overwrite the method getExpectedClass() and specify a type in the derived classes. The value is checked on add() and set() and throws an exception if an error occurs. If you want to use your own logic for the keys, overwrite the add() method.</p>

<p>Additionally the IteratorAggregate interface has been implemented.</p>

<h3>2019-01-29: TranslationEntity</h3>

<p>After the EntityTranslationDefinition has made its way into the system to save boilerplate code, we continue with the Entities.</p>

<p>There is now the TranslationEntity class, which already contains the boilerplate code (properties and methods) and only needs to be extended by its own fields and the relation. <a href="https://github.com/shopware/platform/blob/master/src/Core/Checkout/Customer/Aggregate/CustomerGroupTranslation/CustomerGroupTranslationEntity.php">Here is an example!</a></p>

<h3>2019-01-29: Color-Picker component</h3>

<p>There is a new form component, namely the &lt;sw-color-picker&gt; . With the colorpicker it is possible to select a hex string with a colorpicker UI.</p>

<p>Here is a small example how this can look like in Action:</p>

<pre>
&lt;sw-color-picker
&nbsp;&nbsp; &nbsp;label=&quot;My Color&quot;
&nbsp;&nbsp; &nbsp;:disabled=&quot;disabled&quot;
&nbsp;&nbsp; &nbsp;v-model=&quot;$route.meta.$module.color&quot;&gt;
&lt;/sw-color-picker&gt;</pre>

<h3>2019-01-29: Refactoring plugin system</h3>

<ul>
	<li>To update plugins in the system execute bin/console plugin:refresh
	<ul>
		<li>all Lifecycle commands call the refresh method before, so you don&#39;t need execute the refresh command before the installation of a plugin</li>
	</ul>
	</li>
	<li>a Pre and Post event is fired for every change of the lifecycle state of a plugin</li>
	<li>Every plugin now needs a valid composer.json in the pluugin root directory
	<ul>
		<li>Have a look here, how it have to look like: src/Docs/60-plugin-system/05-plugin-information.md</li>
	</ul>
	</li>
</ul>

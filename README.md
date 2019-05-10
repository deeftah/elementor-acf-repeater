# What is this?
This plugin is an extension to Elementor Pro that allows access to values from ACF Repeater fields.  The field types that are accessible are: Text, URL, Image, Gallery and File.

The general idea:
- Create and style a template of a single row of values from a repeater field.
- Apply the template to all the repeater rows via the ACF Repeater Template widget when used in a Post/Page that contains the repeater field.

The general workflow is as follows:
- Create ACF Field Group that has a Repeater Field.
- Fill out Repeater Field on a Post/Page/CPT that has the field group attached.
- Create a Section Template with Elementor. Set the ACF Repeater Field to use to the one that was created.
- Add widgets that will hold the data from the fields contained in the Repeater field.  Modify the look and publish.
- Edit a Post/Page/CPT/Template with Elementor and drop in the ACF Repeater Template widget.

# How do I use it?
Use the following steps
- Create an ACF Field Group. (Or edit one)
  - Add a repeater field to the field group and populate it with one or more of the aforementioned field types.
  - Save the group.
- Edit a Post/Page/CPT/etc. that matches the Location rules of the field group.
  - Fill out a few rows of the repeater field with data.
- Create an Elementor section template. If you are editing an existing Section template, skip to #4.
  1. Close the popup, if there is one, and exit to the dashboard by click the top left hamburger menu and selecting EXIT TO DASHBOARD.
  2. In the ACF Repeater Selection metabox; select which repeater field you want access to within the template.
  3. Either Save Draft or Publish the Section template.
  4. Edit the Section template with Elementor.
  5. Drag in a widget to hold the repeater field data. The widget must have access to Dynamic Tags.
  6. Click the Dynamic Tags toggle.  This will be at the top right of a setting field and says Dynamic with the database font awesome icon to the right of the text.
  7. Scroll down to the ACF group.  If you do not see the group then the field type is not supported.
  8. Select the 'ACF Repeater *type*' tag that corresponds to a ACF field type of at least on field from the Repeater you chose in step #2.
  9. Click anywhere in the textbox that holds the tag you selected to show the settings popup.
  10. Click the Repeater Field dropdown and select the field you want to get data from.  Continue to step #11 the dropdown is empty, or continue to step #16 if the select box holds the field name you are looking for.
  11. Click the Settings gear icon in the bottom left corner of the screen.
  12. Expand Preview Settings.
  13. Choose a Post/Page/CPT/etc. that has the ACF Repeater field filled out with data.
  14. Click Apply & Preview.
  15. Select the widget you dropped in and start again at step #9.
  16. Repeat from step #5 until you have a widget that corresponds to each field in the Repeater.
  17. Modify the look as you desire.
  18. Publish the template.
- You can now utilize the Section template when editing anything with elementor that has the repeater field you selected in step #2 attached to it.
  - Edit a Post/Page/CPT/Template/etc. with Elementor.
  - Drop in the ACF Repeater Template widget.  Select the section Widget that was created.
  - Modify the layout settings. (TODO: Add layout settings.)

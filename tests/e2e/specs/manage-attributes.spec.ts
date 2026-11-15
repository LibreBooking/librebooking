import { test, expect, type Locator, type Page } from '@playwright/test';

// Resource attributes can be applied to specific resources ("Applies To") and
// limited to resource types ("Collect In Specific Cases"). Both pickers share
// one container in the dialog, so both selections must survive a save and an
// edit that only changes the label.

const RESOURCE_CATEGORY = '4';

async function pick(page: Page, prompt: Locator, choices: Locator, names: string[]) {
  await prompt.click();
  for (const name of names) {
    await choices.getByLabel(name, { exact: true }).check();
  }
  await choices.locator('a.ok').click();
}

function attributeRow(page: Page, label: string) {
  return page.locator('#attributeList tr', { has: page.getByRole('cell', { name: label, exact: true }) });
}

test('resource attribute keeps Applies To and resource type scope after editing', async ({ page }) => {
  const label = `Scoped ${Date.now()}`;
  const renamed = `${label} renamed`;

  await page.goto('admin/manage_attributes.php');
  await page.locator('#attributeCategory').selectOption(RESOURCE_CATEGORY);

  await page.locator('#addAttributeButton').click();
  const addDialog = page.locator('#addAttributeDialog');
  await expect(addDialog).toBeVisible();
  await addDialog.locator('[name="ATTRIBUTE_LABEL"]').fill(label);

  const addChoices = page.locator('#entityChoices');
  await pick(page, page.locator('#appliesTo'), addChoices, ['Conference Room 1', 'Conference Room 2']);
  await page.locator('#attributeLimitScope').check();
  await pick(page, page.locator('#attributeSecondaryEntityDescription'), addChoices, ['Meeting Room']);

  await addDialog.locator('button.save').click();
  await expect(addDialog).toBeHidden();

  const row = attributeRow(page, label);
  await expect(row).toContainText('Conference Room 1, Conference Room 2');
  await expect(row).toContainText('Meeting Room');

  await row.locator('a.edit').click();
  const editDialog = page.locator('#editAttributeDialog');
  await expect(editDialog).toBeVisible();
  await expect(page.locator('#editAttributeLimitScope')).toBeChecked();
  await page.locator('#editAttributeLabel').fill(renamed);
  await editDialog.locator('button.save').click();
  await expect(editDialog).toBeHidden();

  const renamedRow = attributeRow(page, renamed);
  await expect(renamedRow).toContainText('Conference Room 1, Conference Room 2');
  await expect(renamedRow).toContainText('Meeting Room');
});

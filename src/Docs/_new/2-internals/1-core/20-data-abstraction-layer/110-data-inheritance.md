[titleEn]: <>(Data Inheritance)

It is possible to inherit data from a parent entity of the same type this id called *parent/child concept*. The parent record has all required fields filled in and is a valid entity in itself. A child can now optionally overwrite data which is different to the parent.

## Inherit a field

To start using inheritance, you have to update your definition and database.

1. Make inheritable fields nullable in the database
2. Add the `ParentFkField` in your definition
3. Add the `ChildrenAssociationField` in your definition
4. Allow inheritance by overwriting `allowInheritance()`
5. Flag fields as inheritable

### 1. Make fields nullable

```sql
ALTER TABLE `employee` MODIFY `supervisor` VARHCAR(255) NULL;
```

### 2. Add the ParentFkField

```php
new ParentFkField(self::class)
```

### 3. Add the ChildrenAssociationField

```php
new ChildrenAssociationField(self::class)
```

### 4. Allow inheritance

```php
public function allowInheritance(): bool
{
    return true;
}
```

### 5. Flag fields as inheritable

```php
(new StringField('supervisor', 'supervisor'))->addFlags(new Inherited())
```

## Translations

This concept also supports translations. Given a parent/child entity with an
inherited language (de-CH *inherits from* de-DE), the resolution of the 
values will be:

1. Child (de-CH)
2. Child (de-DE)
3. Parent (de-CH)
4. Parent (de-DE)

If an inheritance is not found, the next translation in the chain above will
be used.

### Enable translation inheritance

Assuming your definition is already aware of inheritance, you have to update
your definition and add the `Inherited` flag to your translated fields and
the translation association.

```php
(new TranslatedField('supervisor'))->addFlags(new Inherited()),
(new TranslationsAssociationField(EmployeeTranslationDefinition::class))->addFlags(new Inherited()),
```

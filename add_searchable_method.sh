#!/bin/bash
# Filepath: add_searchable_method.sh

echo "🔍 Adding getFieldsSearchable method to repositories..."

# Find all repository files
REPO_FILES=$(find app/Repositories -name "*Repository.php" | grep -v "BaseRepository.php")

# Count files to process
FILE_COUNT=$(echo "$REPO_FILES" | wc -l)
echo "Found $FILE_COUNT repository files to update."

# Process each file
CURRENT=0
for file in $REPO_FILES; do
  CURRENT=$((CURRENT + 1))
  echo "[$CURRENT/$FILE_COUNT] Processing $file"
  
  # Check if getFieldsSearchable method exists
  if ! grep -q "function getFieldsSearchable" "$file"; then
    # Create backup
    cp "$file" "${file}.bak"
    
    # Find position after fieldSearchable array
    POSITION=$(grep -n "protected \$fieldSearchable" "$file" | cut -d ':' -f1)
    if [ -z "$POSITION" ]; then
      echo "   ⚠️ Could not find fieldSearchable in $file, skipping..."
      continue
    fi
    
    POSITION=$((POSITION + 3))
    
    # Create the method
    METHOD='
    /**
     * Get searchable fields
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
'
    
    # Insert the method
    sed -i "${POSITION}i\\${METHOD}" "$file"
    echo "   ✅ Added getFieldsSearchable method"
  else
    echo "   ✓ Method already exists"
  fi
done

echo "🎉 All repositories updated successfully!"
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "✅ Done! Now try running 'php artisan route:list'"
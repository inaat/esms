<?php

namespace App\Rules;

use App\Models\Lesson;
use Illuminate\Contracts\Validation\Rule;

class uniqueLessonInClass implements Rule
{
    public $class_section_id;
    public $lesson_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($class_section_id, $lesson_id = NULL)
    {
        $this->class_section_id = $class_section_id;
        $this->lesson_id = $lesson_id;
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    public function passes($attribute, $value)
    {
        if ($this->lesson_id == NULL) {
            $count = Lesson::where('name', $value)->where('class_section_id', $this->class_section_id)->count();
            if ($count == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $exclude_exists = Lesson::where('name', $value)->where('id', $this->lesson_id)->count();
            if ($exclude_exists) {
                return true;
            } else {
                $count = Lesson::where('name', $value)->where('class_section_id', $this->class_section_id)->count();
                if ($count == 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Name Already Exists.';
    }
}

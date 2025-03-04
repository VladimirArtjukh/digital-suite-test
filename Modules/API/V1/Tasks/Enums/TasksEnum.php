<?php
declare(strict_types=1);

/**
 * @author Volodymyr Artjukh
 * @copyright 2025 Volodymyr Artjukh (vladimir.artjukh@gmail.com)
 */

namespace Modules\API\V1\Tasks\Enums;

enum TasksEnum: string
{
    case PAGINATE = '5';
    case SUCCESSFULL_DELETE_MESSAGE = 'Task has been deleted successfully';
    case SUCCESSFULL_UPDATE_MESSAGE = 'Task has been updated successfully';

}

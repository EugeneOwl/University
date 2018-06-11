# Working with a doctrine MySQL entities and relations

### Basics of project
There are 2 main entities (1 level):
1. **User**
2. **Task**

There is an entity based on 1 level one User (2 level)
1. **Usergroup**

And there is an entity based on all of previous ones (3 level)
1. **Sprint**

There are also such entities as **tasktype** and **sprintstatus** which are just a little more than descriptive attribute for **Task** and **Sprint**.

Also intermediate tables for '_ManyToMany_' relations.

#### Important moment in architecture
 There is also one intermediate table (like for '_ManyToMany_' relation) for '_OneToMany_' relation. Это решение обосновано
 1. Необходимостью политики уровней (ведь благодаря ему user не знает ничего о usergroup) 
 2. Моими соображениями о неблагоприятности идеи хранить id user-ов в сериализованном массиве у каждой usergroup:
     1. Нарушение формализации и идеи о простоте и атомарности структуры, хранимой в ячейке таблицы
     2. Несогласованность (при удалении пользователя, MySQL не смогла бы сама каскадно удалить его id из сериализованных массивов и прочие аналогичные вещи, которые мне пришлось бы делать самому во всех местах)
     3. Затраты на сериализацию/десериализацию, проверки уникальности вхождения и прочее

Я думаю, решение с промежуточными таблицами лучше, чем с сериализованными массивами id, хотя, конечно, могу и ошибаться.

### Rules
Appropriate tables for each entity are called by _their entity_ name in the _plural_ with a _small letter_.

### User
When log up **user** specifies name of his group and they bind to each other with a bidirectional relation. At home page which is available after authorization each user sees the list of his own tasks and their state (done / not done). Also the tasks and their state from sprints where user participates.

### Admin page
User can get to the admin page if he has an **admin** role privilege. Administrator can:
1. Create new (validation for name uniqueness is present everywhere):
    1. User group
    2. Task type
    3. Task
    4. Sprint status
    5. Sprint
2. Bind existing (validation for tie uniqueness is present everywhere)
    1. Task to user
    2. Sprint to
        1. Task
        2. User
        3. User group
3. Edit existing ones
    1. Match tasks as executed or not executed
    2. Update sprint statuses
### Requirements
1. After admin close sprint (with a special status) he gets a statistics report e.g. "3 / 5 tasks done in 'Rush week' sprint."
2. When admin creates new sprint all of not executed tasks from existing closed sprints automatically get to the new one.
3. System is quite flexible: User without user group, task without task type, user without tasks, tasks without users etc. Each entity is quite independent. 
When configuring entities in each case admin sees the existing exemplars of target entity and some more info.

Forms are not hardcodded into html but implemented as special objects, processed special way in controllers and displayed special way in templates (see _'src/Form'_, _'src/Controller'_, _'templates'_).

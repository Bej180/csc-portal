@php
    $authAuth = auth()->user();
    $todos = $authAuth->todos()->simplePaginate(3);
/*
1 min ago
12 mins ago
an hour ago
3 hours ago
5 hours ago, it the time is greater than 5 hours show Today at 10:20am if it's on the same day, or 
Yesterday at 10:20am if it's previous day

*/
@endphp

<div class="cd" ng-controller="TodoController" ng-init="initTodo()">
    <div class="cd-h">
        <h3 class="cd-t">
            To Do List
        </h3>
        
    </div>

    <div class="cd-b !overflow-y-visible !py-0">
        <ul infinite-scroll="moreTodos()" class="todo-list" data-widget="todo-list">
            <li ng-repeat="todo in todos" class="group relative border-b border-zinc-300 last:border-transparent !pb-3 last:!pb-0" >
              <div class="flex items-center justify-between">
                <x-checkbox ng-model="todo.id"  name="todo_{%todo.id%}" ng-checked="todo.complete" ng-change="check(todo.id)" check="line-through"><span ng-bind="todo.title"></span> <div class="opacity-45 text-xs" ng-bind="todo.time_created"></div></x-checkbox>
              
                <div class="tools group-hover:!block">
                    <i ng-click="deleteTask(todo.id)" class="fas fa-trash text-black hover:text-red-500"></i>
                </div>
              </div>
              
            </li>
            
        </ul>
    </div>
    <div class="cd-f clearfix mt-2">
    <form class="card-footer clearfix flex gap-2">
        
        <div class="flex-1">
          <input ng-model="todo" autocomplete="off" type="text" name="todo" placeholder="Title of Todo" class="input flex-1" />
        </div>

        <button ng-disabled="!todo" type="button" controller="saveTask(todo)"
            class="btn btn-primary btn-adaptive float-right"><i class="fas fa-plus"></i> Add Task</button>

    </form>
  </div>
</div>

{{-- <fieldset class="border-slate-500/50 border p-4 rounded-md my-4" x-data="{todo:null}">

  <legend class="font-bold">
    Todo List 
  </legend>
  <ul>
    @foreach ($todos as $n => $todo)
    <li class="flex items-center gap-2"><input x-on:change="updateTodoList" value="{{$todo->id}}" type="checkbox" class="peer checkbox" id="todo{{$todo->id}}"> <label for="todo{{$todo->id}}" class="flex-1 peer-checked:line-through peer-checked:opacity-45 cursor-pointer">{{$todo->title}}</label></li>
    @endforeach
  </ul>
  
  <div>
    <form action="/todo/add" method="post" class="flex items-center gap-2 w-full justify-between">
      @csrf
      <input x-on:change="todo=$el.value" type="text" name="todo" placeholder="Title of Todo" class="input flex-1"/> <button x-on:click="submitTodo" type="submit" class="btn-primary">Save Todo</button>
    </form>
  </div>
</fieldset> --}}

<?php

namespace Nasirkhan\Admin\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Nasirkhan\ModuleManager\Modules\Category\Models\Category;
use Nasirkhan\ModuleManager\Modules\Post\Models\Post;
use Nasirkhan\ModuleManager\Modules\Tag\Models\Tag;

class AdminDashboard extends Component
{
    public int $postsCount;
    public int $categoriesCount;
    public int $tagsCount;
    public int $usersCount;
    public int $rolesCount;

    public function mount(): void
    {
        $this->postsCount = Post::count();
        $this->categoriesCount = Category::count();
        $this->tagsCount = Tag::count();
        $this->usersCount = User::count();
        $this->rolesCount = Role::count();
    }

    public function render()
    {
        return view('admin::livewire.dashboard');
    }
}

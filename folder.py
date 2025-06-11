import os

def print_tree(start_path, prefix=''):
    items = sorted(os.listdir(start_path))
    for index, item in enumerate(items):
        path = os.path.join(start_path, item)
        connector = '└── ' if index == len(items) - 1 else '├── '
        print(prefix + connector + item)
        if os.path.isdir(path):
            extension = '    ' if index == len(items) - 1 else '│   '
            print_tree(path, prefix + extension)

if __name__ == "__main__":
    # Update this to the path of your project folder
    root_dir = r"D:\Projects\flyhub-ERP"
    
    print(f"\n📂 Folder structure for: {root_dir}\n")
    print_tree(root_dir)

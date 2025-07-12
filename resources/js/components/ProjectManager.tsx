import React, { useState } from 'react';
import { useTaskStore } from '../store/useTaskStore';
import { motion, AnimatePresence } from 'framer-motion';
import { Plus, Edit2, Trash2, Check, X } from 'lucide-react';

export function ProjectManager() {
  const { projects, addProject, deleteProject, updateProject } = useTaskStore();
  const [newProjectName, setNewProjectName] = useState('');
  const [isAdding, setIsAdding] = useState(false);
  const [editingProjectId, setEditingProjectId] = useState<number | null>(null);
  const [editingProjectName, setEditingProjectName] = useState('');

  const handleAddProject = async (e: React.FormEvent) => {
    e.preventDefault();
    if (newProjectName.trim()) {
      await addProject(newProjectName.trim());
      setNewProjectName('');
      setIsAdding(false);
    }
  };

  const startEdit = (id: number, name: string) => {
    setEditingProjectId(id);
    setEditingProjectName(name);
  };

  const cancelEdit = () => {
    setEditingProjectId(null);
    setEditingProjectName('');
  };

  const saveEdit = async () => {
    if (editingProjectId && editingProjectName.trim()) {
      await updateProject(editingProjectId, editingProjectName.trim());
      cancelEdit();
    }
  };

  return (
    <div className="mb-8 bg-gray-50 rounded-lg p-4">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-semibold text-gray-900">Projects</h3>
        {!isAdding && (
          <button
            onClick={() => setIsAdding(true)}
            className="flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700"
          >
            <Plus size={16} />
            Add Project
          </button>
        )}
      </div>

      <AnimatePresence mode="popLayout">
        {isAdding && (
          <motion.form
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            onSubmit={handleAddProject}
            className="mb-4"
          >
            <div className="flex gap-2">
              <input
                type="text"
                value={newProjectName}
                onChange={(e) => setNewProjectName(e.target.value)}
                placeholder="Project name"
                className="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg
                         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                autoFocus
              />
              <button
                type="submit"
                disabled={!newProjectName.trim()}
                className="px-3 py-1.5 text-sm text-white bg-blue-500 rounded-lg
                         hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Add
              </button>
              <button
                type="button"
                onClick={() => setIsAdding(false)}
                className="px-3 py-1.5 text-sm text-gray-600 bg-gray-200 rounded-lg
                         hover:bg-gray-300"
              >
                Cancel
              </button>
            </div>
          </motion.form>
        )}

        <div className="space-y-2">
          {projects.map((project) => (
            <motion.div
              key={project.id}
              layout
              className="flex items-center justify-between p-2 bg-white rounded-lg
                       border border-gray-200 group"
            >
              <div className="flex items-center gap-2 flex-1">
                <div
                  className="w-3 h-3 rounded-full"
                  style={{ backgroundColor: project.color }}
                />
                {editingProjectId === project.id ? (
                  <input
                    type="text"
                    value={editingProjectName}
                    onChange={(e) => setEditingProjectName(e.target.value)}
                    onKeyDown={(e) => {
                      if (e.key === 'Enter') saveEdit();
                      if (e.key === 'Escape') cancelEdit();
                    }}
                    className="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                    autoFocus
                  />
                ) : (
                  <span className="text-sm font-medium text-gray-900">
                    {project.name}
                  </span>
                )}
              </div>
              <div className="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                {editingProjectId === project.id ? (
                  <>
                    <button
                      onClick={saveEdit}
                      className="p-1 text-green-600 hover:text-green-700"
                      title="Save"
                    >
                      <Check size={16} />
                    </button>
                    <button
                      onClick={cancelEdit}
                      className="p-1 text-red-600 hover:text-red-700"
                      title="Cancel"
                    >
                      <X size={16} />
                    </button>
                  </>
                ) : (
                  <>
                    <button
                      onClick={() => startEdit(project.id, project.name)}
                      className="p-1 text-gray-500 hover:text-blue-600"
                      title="Edit project"
                    >
                      <Edit2 size={14} />
                    </button>
                    <button
                      onClick={() => deleteProject(project.id)}
                      className="p-1 text-gray-500 hover:text-red-600"
                      title="Delete project"
                    >
                      <Trash2 size={14} />
                    </button>
                  </>
                )}
              </div>
            </motion.div>
          ))}
        </div>
      </AnimatePresence>
    </div>
  );
}
